<?php

return [
    'required' => ':attributeを入力してください。',
    'email'    => ':attributeは正しいメールアドレスの形式で入力してください。',
    'min'      => [
        'string' => ':attributeは:min文字以上で入力してください。',
    ],
    'max'      => [
        'string' => ':attributeは:max文字以下で入力してください。',
    ],
    'confirmed' => ':attributeが一致しません。',
    'unique'    => 'この:attributeはすでに登録されています。',
    'string'    => ':attributeは文字列で入力してください。',

    'attributes' => [
        'email'            => 'メールアドレス',
        'password'         => 'パスワード',
        'name'             => '氏名',
        'company_name'     => '会社名',
        'password_confirmation' => '確認用パスワード',
    ],
];
