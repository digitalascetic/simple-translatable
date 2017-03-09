<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 14/03/16
 * Time: 14:32
 */

namespace DigitalAscetic\SimpleTranslatable\Service;


class TranslatableException extends \RuntimeException {

    /**
     * TranslatableException constructor.
     */
    public function __construct($message) {
        parent::__construct($message);
    }

}