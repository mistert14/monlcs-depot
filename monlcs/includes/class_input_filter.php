<?php
 
 class InputFilter {
       var $tagsArray;            // default = empty array
       var $attrArray;            // default = empty array
   
       var $tagsMethod;        // default = 0
       var $attrMethod;        // default = 0
   
       var $xssAuto;           // default = 1
       var $tagBlacklist = array('applet', 'body', 'bgsound', 'base', 'basefont', 'embed', 'frame', 'frameset', 'head', 'html', 'id', 'iframe', 'ilayer', 'layer', 'link', 'meta', 'name', 'object', 'script', 'style', 'title', 'xml');
       var $attrBlacklist = array('action', 'background', 'codebase', 'dynsrc', 'lowsrc');  // also will strip ALL event handlers
   
 function inputFilter($tagsArray = array(), $attrArray = array(), $tagsMethod = 0, $attrMethod = 0, $xssAuto = 1) {
           // make sure user defined arrays are in lowercase
           for ($i = 0; $i < count($tagsArray); $i++) $tagsArray[$i] = strtolower($tagsArray[$i]);
           for ($i = 0; $i < count($attrArray); $i++) $attrArray[$i] = strtolower($attrArray[$i]);
           // assign to member vars
           $this->tagsArray = (array) $tagsArray;
           $this->attrArray = (array) $attrArray;
           $this->tagsMethod = $tagsMethod;
           $this->attrMethod = $attrMethod;
           $this->xssAuto = $xssAuto;
       }
   
 function process($source) {
           // clean all elements in this array
           if (is_array($source)) {
               foreach($source as $key => $value)
                   // filter element for XSS and other 'bad' code etc.
                   if (is_string($value)) $source[$key] = $this->remove($this->decode($value));
               return $source;
           // clean this string
           } else if (is_string($source)) {
               // filter source for XSS and other 'bad' code etc.
               return $this->remove($this->decode($source));
           // return parameter as given
           } else return $source;
       }
   

  	function remove($source) {
          $loopCounter=0;
          // provides nested-tag protection
          while($source != $this->filterTags($source)) {
              $source = $this->filterTags($source);
              $loopCounter++;
          }
          return $source;
      }
  
 	function filterTags($source) {
          // filter pass setup
          $preTag = NULL;
          $postTag = $source;
          // find initial tag's position
          $tagOpen_start = strpos($source, '<');
          // interate through string until no tags left
          while($tagOpen_start !== FALSE) {
              // process tag interatively
              $preTag .= substr($postTag, 0, $tagOpen_start);
              $postTag = substr($postTag, $tagOpen_start);
              $fromTagOpen = substr($postTag, 1);
              // end of tag
              $tagOpen_end = strpos($fromTagOpen, '>');
              if ($tagOpen_end === false) break;
              // next start of tag (for nested tag assessment)
              $tagOpen_nested = strpos($fromTagOpen, '<');
              if (($tagOpen_nested !== false) && ($tagOpen_nested < $tagOpen_end)) {
                  $preTag .= substr($postTag, 0, ($tagOpen_nested+1));
                  $postTag = substr($postTag, ($tagOpen_nested+1));
                  $tagOpen_start = strpos($postTag, '<');
                  continue;
              }
              $tagOpen_nested = (strpos($fromTagOpen, '<') + $tagOpen_start + 1);
              $currentTag = substr($fromTagOpen, 0, $tagOpen_end);
              $tagLength = strlen($currentTag);
              if (!$tagOpen_end) {
                  $preTag .= $postTag;
                  $tagOpen_start = strpos($postTag, '<');
              }
              // iterate through tag finding attribute pairs - setup
              $tagLeft = $currentTag;
              $attrSet = array();
              $currentSpace = strpos($tagLeft, ' ');
              // is end tag
              if (substr($currentTag, 0, 1) == "/") {
                  $isCloseTag = TRUE;
                  list($tagName) = explode(' ', $currentTag);
                  $tagName = substr($tagName, 1);
              // is start tag
              } else {
                  $isCloseTag = FALSE;
                  list($tagName) = explode(' ', $currentTag);
              }
              // excludes all "non-regular" tagnames OR no tagname OR remove if xssauto is on and tag is blacklisted
              if ((!preg_match("/^[a-z][a-z0-9]*$/i",$tagName)) || (!$tagName) || ((in_array(strtolower($tagName), $this->tagBlacklist)) && ($this->xssAuto))) {
                  $postTag = substr($postTag, ($tagLength + 2));
                  $tagOpen_start = strpos($postTag, '<');
                  // don't append this tag
                  continue;
              }
              // this while is needed to support attribute values with spaces in!
              while ($currentSpace !== FALSE) {
                  $fromSpace = substr($tagLeft, ($currentSpace+1));
                  $nextSpace = strpos($fromSpace, ' ');
                  $openQuotes = strpos($fromSpace, '"');
                  $closeQuotes = strpos(substr($fromSpace, ($openQuotes+1)), '"') + $openQuotes + 1;
                  // another equals exists
                  if (strpos($fromSpace, '=') !== FALSE) {
                      // opening and closing quotes exists
                      if (($openQuotes !== FALSE) && (strpos(substr($fromSpace, ($openQuotes+1)), '"') !== FALSE))
                          $attr = substr($fromSpace, 0, ($closeQuotes+1));
                      // one or neither exist
                      else $attr = substr($fromSpace, 0, $nextSpace);
                  // no more equals exist
                  } else $attr = substr($fromSpace, 0, $nextSpace);
                  // last attr pair
                  if (!$attr) $attr = $fromSpace;
                  // add to attribute pairs array
                  $attrSet[] = $attr;
                  // next inc
                  $tagLeft = substr($fromSpace, strlen($attr));
                  $currentSpace = strpos($tagLeft, ' ');
              }
              // appears in array specified by user
             $tagFound = in_array(strtolower($tagName), $this->tagsArray);
              // remove this tag on condition
             if ((!$tagFound && $this->tagsMethod) || ($tagFound && !$this->tagsMethod)) {
                  // reconstruct tag with allowed attributes
                  if (!$isCloseTag) {
                      $attrSet = $this->filterAttr($attrSet);
                      $preTag .= '<' . $tagName;
                      for ($i = 0; $i < count($attrSet); $i++)
                          $preTag .= ' ' . $attrSet[$i];
                      // reformat single tags to XHTML
                      if (strpos($fromTagOpen, "</" . $tagName)) $preTag .= '>';
                      else $preTag .= ' />';
                  // just the tagname
                  } else $preTag .= '</' . $tagName . '>';
              }
             // find next tag's start
              $postTag = substr($postTag, ($tagLength + 2));
              $tagOpen_start = strpos($postTag, '<');
          }
          // append any code after end of tags
          $preTag .= $postTag;
          return $preTag;
      }
  
 function filterAttr($attrSet) {
           $newSet = array();
           // process attributes
           for ($i = 0; $i <count($attrSet); $i++) {
               // skip blank spaces in tag
               if (!$attrSet[$i]) continue;
               // split into attr name and value
               $attrSubSet = explode('=', trim($attrSet[$i]),2);
               list($attrSubSet[0]) = explode(' ', $attrSubSet[0]);
               // removes all "non-regular" attr names AND also attr blacklisted
               if ((!eregi("^[a-z]*$",$attrSubSet[0])) || (($this->xssAuto) && ((in_array(strtolower($attrSubSet[0]), $this->attrBlacklist)) || (substr($attrSubSet[0], 0, 2) == 'on'))))
                   continue;
               // xss attr value filtering
               if ($attrSubSet[1]) {
                   // strips unicode, hex, etc
                   $attrSubSet[1] = str_replace('&#', '', $attrSubSet[1]);
                   // strip normal newline within attr value
                   $attrSubSet[1] = preg_replace('/\s+/', '', $attrSubSet[1]);
                   // strip double quotes
                   $attrSubSet[1] = str_replace('"', '', $attrSubSet[1]);
                   // [requested feature] convert single quotes from either side to doubles (Single quotes shouldn't be used to pad attr value)
                   if ((substr($attrSubSet[1], 0, 1) == "'") && (substr($attrSubSet[1], (strlen($attrSubSet[1]) - 1), 1) == "'"))
                       $attrSubSet[1] = substr($attrSubSet[1], 1, (strlen($attrSubSet[1]) - 2));
                   // strip slashes
                   $attrSubSet[1] = stripslashes($attrSubSet[1]);
               }
               // auto strip attr's with "javascript:
              if (InputFilter::badAttributeValue( $attrSubSet ))
                   continue;
   
               // if matches user defined array
               $attrFound = in_array(strtolower($attrSubSet[0]), $this->attrArray);
               // keep this attr on condition
               if ((!$attrFound && $this->attrMethod) || ($attrFound && !$this->attrMethod)) {
                   // attr has value
                   if ($attrSubSet[1]) $newSet[] = $attrSubSet[0] . '="' . $attrSubSet[1] . '"';
                   // attr has decimal zero as value
                   else if ($attrSubSet[1] == "0") $newSet[] = $attrSubSet[0] . '="0"';
                   // reformat single attributes to XHTML
                   else $newSet[] = $attrSubSet[0] . '="' . $attrSubSet[0] . '"';
               }
           }
           return $newSet;
       }

 	function badAttributeValue( $attrSubSet ) {
          $attrSubSet[0] = strtolower( $attrSubSet[0] );
          $attrSubSet[1] = strtolower( $attrSubSet[1] );
          return (
              ((strpos($attrSubSet[1], 'expression') !== false) && ($attrSubSet[0]) == 'style') ||
              (strpos($attrSubSet[1], 'javascript:') !== false) ||
              (strpos($attrSubSet[1], 'behaviour:') !== false) ||
              (strpos($attrSubSet[1], 'vbscript:') !== false) ||
              (strpos($attrSubSet[1], 'mocha:') !== false) ||
             (strpos($attrSubSet[1], 'livescript:') !== false)
          );
      }
  

  	function decode($source) {
         // url decode
          $source = html_entity_decode($source, ENT_QUOTES, "ISO-8859-1");
          // convert decimal
         $source = preg_replace('/&#(\d+);/me',"chr(\\1)", $source);                // decimal notation
          // convert hex
          $source = preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)", $source);    // hex notation
          return $source;
      }
  
 
  	function safeSQL($source, &$connection) {
           // clean all elements in this array
           if (is_array($source)) {
               foreach($source as $key => $value)
                   // filter element for SQL injection
                   if (is_string($value)) $source[$key] = $this->quoteSmart($this->decode($value), $connection);
               return $source;
           // clean this string
           } else if (is_string($source)) {
               // filter source for SQL injection
               if (is_string($source)) return $this->quoteSmart($this->decode($source), $connection);
           // return parameter as given
           } else return $source;
       }
   
 
   	function escapeString($string, &$connection) {
           // depreciated function
           if (version_compare(phpversion(),"4.3.0", "<")) mysql_escape_string($string);
           // current function
           else mysql_real_escape_string($string);
           return $string;
       }
   }
   
  ?>
