<?php

class errortoException extends Exception {
    public function setLine($line) {
        $this->line=$line;
    }

    public function setFile($file) {
        $this->file=$file;
    }
}

?>