<?php
class ParameterWidget extends Widget{
    public function render($data){
        $content = C($data);
        return $content;
   } 
}