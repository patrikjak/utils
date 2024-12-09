<?php

return [

    'accepted' => '{1} :attribute musí byť akceptovaný|{2} :attribute musí byť akceptovaná|{3} :attribute musí byť akceptované',
    'in' => '{1} Vybraná :attribute je neplatný|{2} Vybraná :attribute je neplatná|{3} Vybrané :attribute je neplatné',
    'max' => [
        'array' => ':attribute nesmie mať viac ako :max položiek.',
        'file' => ':attribute nesmie mať viac ako :max kilobajtov.',
        'numeric' => '{1} :attribute nesmie byť väčší ako :max.|{2} :attribute nesmie byť väčšia ako :max.|{3} :attribute nesmie byť väčšie ako :max.',
        'string' => '{1} :attribute nesmie mať viac ako :max znak|[2,5] :attribute nesmie mať viac ako :max znaky|[6,*] :attribute nesmie mať viac ako :max znakov',
        'max_digits' => ':attribute nesmie mať viac ako :max číslic.',
    ],
    'min' => [
        'array' => ':attribute musí mať aspoň :min položiek.',
        'file' => ':attribute musí mať aspoň :min kilobajtov.',
        'numeric' => ':attribute musí byť hodnotu aspoň :min',
        'string' => '{1} :attribute musí mať aspoň :min znak|[2,5] :attribute musí mať aspoň :min znaky|[6,*] :attribute musí mať aspoň :min znakov',
        'min_digits' => ':attribute musí mať aspoň :min číslic.',
    ],
    'not_in' => '{1} Vybraný :attribute je neplatný|{2} Vybraná :attribute je neplatná|{3} Vybrané :attribute je neplatné',
    'password' => [
        'reset' => 'Vaše heslo bolo obnovené',
        'sent' => 'Na Vašu e-mailovú adresu sme poslali link na obnovu hesla',
        'throttled' => 'Prosím počkajte pred skúšaním znova',
        'token' => 'Token pre obnovu hesla je neplatný',
        'user' => 'Nemôžeme nájsť používateľa s touto e-mailovou adresou',
        'mixed' => 'Heslo musí obsahovať aspoň jedno malé a jedno veľké písmeno',
        'changed' => 'Vaše heslo bolo zmenené',
        'current' => 'Poskytnuté heslo sa nezhoduje s aktuálnym',
        'min' => 'Heslo musí mať minimálne 8 znakov',
        'max' => 'Heslo môže mať maximálne 191 znakov',
    ],
    'required' => '{1} :attribute je povinný|{2} :attribute je povinná|{3} :attribute je povinné',
    'telephone_format' => 'attribute musí byť platné telefónne číslo vo formáte :format',
    'unique' => '{1} :attribute je už použitý|{2} :attribute je už použitá|{3} :attribute je už použité',

];