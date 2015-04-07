<?php

$template = new template;
$template->load("index");

$tmpl = $template->display(true);
$tmpl = $template->operators();
echo $tmpl;