<?php
namespace Application\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Application\Filter\PhoneFilter;
use Application\Validator\PhoneValidator;

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
    // Add "name" field
    $this->add([
      'type'  => 'text',
      'name' => 'name',
      'attributes' => [
        'id' => 'name'
      ],
      'options' => [
        'label' => 'Name',
      ],
    ]);

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
        'label' => 'Your Phone. (only numbers)',
      ],
    ]);

  }


    // This method creates input filter (used for form filtering/validation).
  private function addInputFilter() 
  {
    // Get the default input filter attached to form model.
    $inputFilter = $this->getInputFilter();

    $inputFilter->add([
      'name'     => 'name',
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
         'name' => 'StringLength',
           'options' => [
             'min' => 5,
             'max' => 20
           ],
        ],
     ],
    ]
  );

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
        /*[ // Fazendo uso do método filterPhone aqui nessa própria classe
          'name' => 'Callback',
          'options' => [
            'callback' => [$this, 'filterPhone'],
            'callbackParams' => [
              'format' => 'intl'
            ]
          ]                        
        ],*/

        [   //Fazendo uso da classe PhoneFiter em: /var/www/html/zf3/estudozf3book/module/Application/src/Filter/PhoneFilter.php
            'name' => PhoneFilter::class,
            'options' => [
                'format' => PhoneFilter::PHONE_FORMAT_INTL
            ]
        ],    
      ], 
      
      'validators' => [
          [
              'name'    => 'StringLength',
              'options' => [
                  'min' => 3,
                  'max' => 32
              ],
          ],
          /*[ // Fazendo uso do método filterPhone aqui nessa própria classe
              'name' => 'Callback',
              'options' => [
                  'callback' => [$this, 'validatePhone'],
                  'callbackOptions' => [
                      'format' => 'intl'
                  ]
              ]                        
          ],*/
          [   //Fazendo uso da classe PhoneFiter em: /var/www/html/zf3/estudozf3book/module/Application/src/Validator/PhoneValidator.php
              'name' => PhoneValidator::class,
              'options' => [
                  'format' => PhoneValidator::PHONE_FORMAT_INTL
              ]                        
          ],
      ],

    ]);
    
  }

  /**
     * Custom filter for a phone number.
     * @param string $value User-entered phone number.
     * @param string $format Desired phone format ('intl' or 'local').
     * @return string Phone number in form of "1 (808) 456-7890" or "123-4567".
     */
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



  /**
     * Custom validator for a phone number.
     * @param string $value Phone number in form of "1 (808) 456-7890"
     * @params array $context Form field values.
     * @param string $format Phone format ('intl' or 'local').
     * @return boolean true if phone format is correct; otherwise false.
     */
    public function validatePhone($value, $context, $format) {
                
      // Determine the correct length and pattern of the phone number,
      // depending on the format.
      if($format == 'intl') {
          $correctLength = 16;
          $pattern = '/^\d\ (\d{3}\) \d{3}-\d{4}$/';
      } else { // 'local'
          $correctLength = 8;
          $pattern = '/^\d{3}-\d{4}$/';
      }
              
      // Check phone number length.
      if(strlen($value)!=$correctLength)
          return false;

      // Check if the value matches the pattern.
      $matchCount = preg_match($pattern, $value);
      
      return ($matchCount!=0)?true:false;
    }







    

}