<?php
    $oFCKeditor = new FCKeditor($fckEditorName) ;
    $sBasePath = FCK_EDITOR_URL;

    $oFCKeditor->BasePath	= $sBasePath ;
    $oFCKeditor->Value		= $fckEditorValue ;
    $oFCKeditor->Height		= $fckEditorHeight ;
    $oFCKeditor->Width		= $fckEditorWidth ;
    $oFCKeditor->Create() ;
?>