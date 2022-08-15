<?php
namespace Application\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;

/**
 * This form is used to collect user feedback data like user E-mail, 
 * message subject and text.
 */
class ContactForm extends Form
{
  // Constructor.   
  public function __construct()
  {
    // Define form name
    parent::__construct('contact-form');

    // Set POST method for this form
    $this->setAttribute('method', 'post');
        	
    // Add form elements
    $this->addElements();
    
    // ... call this method to add filtering/validation rules
    $this->addInputFilter();
  }
    
  // This method adds elements to form (input fields and 
  // submit button).
  private function addElements() 
  {
    // Add "email" field
    $this->add([
      'type'  => 'text',
      'name' => 'email',
      'attributes' => [
        'id' => 'email'
      ],
      'options' => [
        'label' => 'Your E-mail',
      ],
    ]);

    // Add "subject" field
    $this->add([
      'type'  => 'text',
      'name' => 'subject',
      'attributes' => [
        'id' => 'subject'
      ],
      'options' => [
        'label' => 'Subject',
      ],
    ]);

    // Add "body" field
    $this->add([
      'type'  => 'textarea',
      'name' => 'body',			
      'attributes' => [                
        'id' => 'body'
      ],
      'options' => [
        'label' => 'Message Body',
      ],
    ]);
        
    // Add the submit button
    $this->add([
      'type'  => 'submit',
      'name' => 'submit',
      'attributes' => [                
        'value' => 'Submit',
      ],
    ]);

    // Add "phone" field
    $this->add([
      'type'  => 'text',
      'name' => 'phone',
      'attributes' => [                
        'id' => 'phone'
      ],
      'options' => [
        'label' => 'Your Phone',
      ],
    ]);

  }


    // This method creates input filter (used for form filtering/validation).
  private function addInputFilter() 
  {
    // Get the default input filter attached to form model.
    $inputFilter = $this->getInputFilter();
        
    $inputFilter->add([
        'name'     => 'email',
        'required' => true,
        'filters'  => [
           ['name' => 'StringTrim',
            'options' => [                  // adicionado por mim
               'charlist' => "\r\n\t "      // adicionado por mim
             ]                              // adicionado por mim	 
           ],                    
        ],                
        'validators' => [
           [
            'name' => 'EmailAddress',
            'options' => [
              'allow' => \Laminas\Validator\Hostname::ALLOW_DNS,
              'useMxCheck' => false,                            
            ],
          ],
        ],
      ]
    );
        
    $inputFilter->add([
        'name'     => 'subject',
        'required' => true,
        'filters'  => [
           ['name' => 'StringTrim'],
           ['name' => 'StripTags'],
           ['name' => 'StripNewlines'],
        ],                
        'validators' => [
           [
            'name' => 'StringLength',
              'options' => [
                'min' => 5,
                'max' => 128
              ],
           ],
        ],
      ]
    );
    
    $inputFilter->add([
        'name'     => 'body',
        'required' => true,
        'filters'  => [                    
          ['name' => 'StripTags'],
        ],                
        'validators' => [
          [
            'name' => 'StringLength',
            'options' => [
              'min' => 5,
              'max' => 4096
            ],
          ],
        ],
      ]
    );
    
    // phone field    
    $inputFilter->add([
      'name'     => 'phone',
      'required' => true,
      'filters'  => [                    
        [
          'name' => 'Callback',
          'options' => [
            'callback' => [$this, 'filterPhone'],
            'callbackParams' => [
              'format' => 'intl'
            ]
          ]                        
        ],
      ],                                
    ]);
    
  }


  // Custom filter for a phone number.
  public function filterPhone($value, $format) 
  {
    if(!is_scalar($value)) {
      // Return non-scalar value unfiltered.
      return $value;
    }
            
    $value = (string)$value;
        
    if(strlen($value)==0) {
      // Return empty value unfiltered.
      return $value;
    }
        
    // First, remove any non-digit character.
    $digits = preg_replace('#[^0-9]#', '', $value);
        
    if($format == 'intl') {            
      // Pad with zeros if the number of digits is incorrect.
      $digits = str_pad($digits, 11, "0", STR_PAD_LEFT);

      // Add the braces, the spaces, and the dash.
      $phoneNumber = substr($digits, 0, 1) . ' ('.
                     substr($digits, 1, 3) . ') ' .
                     substr($digits, 4, 3) . '-'. 
                     substr($digits, 7, 4);
    } else { // 'local'
      // Pad with zeros if the number of digits is incorrect.
      $digits = str_pad($digits, 7, "0", STR_PAD_LEFT);

      // Add the dash.
      $phoneNumber = substr($digits, 0, 3) . '-'. substr($digits, 3, 4);
    }
        
    return $phoneNumber;               
  }
    

}