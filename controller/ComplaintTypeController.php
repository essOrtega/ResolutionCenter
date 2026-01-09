<?php
require_once __DIR__ . '/../model/ComplaintType.php';

class ComplaintTypeController {

    public function getTypes() {
        $type = new ComplaintType();
        return $type->getAllTypes();
    }
}
