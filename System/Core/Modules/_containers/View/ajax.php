<?php
if (!extension_loaded('json'))
    dl('json.so');
echo json_encode($content);