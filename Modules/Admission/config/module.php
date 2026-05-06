<?php

return array (
  'name' => 'Admission',
  'type' => 'domain',
  'enabled' => true,
  'enable_pdf_convert' => env('ENABLE_PDF_CONVERT', false),
  'tables' => 
  array (
    0 => 'admission_locations',
    1 => 'admission_applications',
    2 => 'admission_catalogs',
  ),
);
