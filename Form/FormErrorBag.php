<?php

namespace Perfico\CoreBundle\Form;

use Symfony\Component\Form\Form;

class FormErrorBag
{
    /**
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * Convert all form errors to JSON format
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->getAssociatedErrors());
    }

    /**
     *
     * @return array
     */
    public function getAssociatedErrors()
    {
        $errors = array();
        foreach ($this->form->all() as $property => $child) {
            $e = array();

            foreach ($child->getErrors() as $error) {
                $e[] = $error->getMessage();
            }

            if (count($e) > 0) {
                $errors[$property] = $e;
            }

            if (count($child->all()) > 0) {
                $form = new self($child);
                $subErrors = $form->getAssociatedErrors();
                if (count($subErrors)) {
                    $subformIndex = $property;
                    $errors[$subformIndex] = $form->getAssociatedErrors();
                }
            }
        }

        if (count($this->form->getErrors())) {
            foreach($this->form->getErrors() as $error) {
                $errors['_form'][] = $error->getMessage();
            }
        }

        return $errors;
    }
}