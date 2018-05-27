# Arr

Classe com um conjunto de métodos para trabalhar com arrays. 

-----------------

* [Começando](#begin)
* [Dot Notation](#dot-notation)
* [Alterando um Array](#change-array)
    * [Adicionando](#change-add)
    * [Removendo](#change-remove)
    * [Adicionando de forma Avançada](#change-advanced-add) 
* [Acessando Informações](#get-info)
    * [Tamanho do Array](#size) 
* [Verificando um Array](#array-check) 
* [Ordenando um Array](#order) 
* [Pesquisando em um Array](#search-array)
* [Transformando um Array](#transform-array)
    * [Alterando a saída Array](#change-out-array) 


---------

[all](#all) | [add](#add) | [assocToKey](#assocToKey) | [clear](#clear) | [count](#count) | [delete](#delete) | [empty](#empty) | [findKey](#findKey) | [first](#first) | [firstKey](#firstKey) | [get](#get) | [get with dot notation](#get-dot) | [getMaxDimensions](#getMaxDimensions) | [has](#has) | [inArray](#inArray)  | [insert](#insert) | [isAssoc](#isAssoc) | [isIndexed](#isIndexed) | [isMulti](#isMulti) | [keyCaseToLower](#keyCaseToLower) | [keyCaseToUpper](#keyCaseToUpper) | [keyToAssoc](#keyToAssoc)  | [last](#last) | [lastKey](#lastKey) | [make](#make)  | [mapKey](#mapKey) |  [orderByKey](#orderByKey) | [pop](#pop) | [prepend](#prepend) | [pull](#pull) | [push](#push) | [reverseAssoc](#reverseAssoc)  [set](#set) | [shift](#shift) | [toAssoc](#toAssoc) |

--------
 
 
## <a name="begin">Começando</a>

A Classe `Arr` pode ser inicializada de várias formas:

### Instanciando:

```php
    $arr = new Simpla\Arr\Arr([1, 2, 3, 4]);
```

### Estaticamente:

Aqui devemos utilizar o método `make`:
 
##### <a name="make">make()</a>


```php
    $arr = Simpla\Arr\Arr::make([1, 2 ,3 ,4]);
```
Esa opção sobescreve todos os dados existentes no array até então.

### Adicionando em um array em branco

```php
    $arr = new Simpla\Arr\Arr();
    $arr[] = [1, 2, 3, 4];    
```

### Pegando os dados do array

#### Todo o array:


##### <a name="get">get()</a> & <a name="all">all()</a>

```php
    print_r($arr->get());
// ou
    print_r($arr->all());

// Array
// (
//    [0] => 1
//    [1] => 2
//    [2] => 3
//    [3] => 4
// )
```

#### Pegando um valor:

```php
    print_r($arr->get(1));
    //2 
```

#### Pegando com ArrayAccess

```php
    print_r($arr[1]);
    //2 
```



## <a name="dot-notation">Dot Notation</a>

Esse tipo de notação é utilizado como uma forma mais prática de acessar os elementos de um array.

```php

    $arr["app"]["cache"]["db"] = "redis";

    //ou
    $arr->set("app.cache.ab.redis");
```

##### <a name="get-dot">get()</a>

Podemos também recuperar um valor utilizando esta notação:

```php
    $arr->get("app.cache.db"); //redis
```


## <a name="change-array">Alterando um Array</a>


### <a name="change-add">Adicionando</a>

#### Adicionando com ArrayAccess

```php
    $arr['name'] = 'Fernando';
    $arr['age'] = 21;
    $arr['address'] = 'Rua Direita,43';
    $arr['occupation'] = 'Padeiro';

// Array
// (
//     [name] => Fernando
//     [idade] => 21
//     [address] => Rua Direita,43
//     [profissao] => Padeiro
// )
```

#### Adicionando no início com 'prepend'

##### <a name="prepend">prepend()</a>

```php
    $arr->prepend('id', 32);

// Array
// (
//     **[id] => 32**
//     [name] => Fernando
//     [idade] => 21
//     [address] => Rua Direita,43
//     [profissao] => Padeiro
// )
```

#### Adicionando no final com 'add'

##### <a name="add">add()</a> & <a name="push">push()</a>

O mesmo que `push`.

```php
    $arr->add('contry', 'Brasil');
    // ou
    $arr->push('contry', 'Brasil');

// Array
// (
//     [id] => 32
//     [name] => Fernando
//     [idade] => 21
//     [address] => Rua Direita,43
//     [profissao] => Padeiro
//     **[contry] => Brasil**
// )
```

#### Inserindo em uma posição com 'insert'

##### <a name="insert">insert()</a> 

```php
        $arr->insert('sexo', 'M', 2);
        $arr->insert('telefone', 35313396, 4);

// Array
// (
//     [id] => 32
//     [name] => Fernando
//     **[sexo] => M**
//     [idade] => 21
//     **[telefone] => 35313396**
//     [address] => Rua Direita,43
//     [profissao] => Padeiro
//     [contry] => Brasil
// )
```
 
### <a name="change-remove">Removendo</a>

#### Removendo no início e retorna o item removido com 'pull'

##### <a name="pull">pull()</a> & <a name="shift">shift()</a>

O mesmo que `shift`.

```php
    $arr->pull();
    // ou
    $arr->shift();
//  32
```
##### Removendo no início e mantendo a estrutura do Array

```php
    $arr->pull(Simpla\Arr\Arr::ARR_PRESERVE_KEY);
//     [id] => 32

```

##### Removendo um array ou item específico pela chave

```php 
    $arr->pull('profissao');
//  'padeiro'

```

#### Removendo no final e retorna o item removido com 'pop'

##### <a name="pop">pop()</a> 

```php
    $arr->pop();
//  'Brasil'
``` 

##### Removendo no final e mantendo a estrutura do Array

```php
    $arr->pop(Simpla\Arr\Arr::ARR_PRESERVE_KEY);
//     [contry] => Brasil

``` 

##### Removendo um array ou item específico pela chave

```php 
    $arr->pop('profissao');
//  'padeiro'

```

#### Removendo com 'delete'

##### <a name="delete">delete()</a> 

Obtem-se o mesmo resultado com `$arr->pop($key)` e `$arr->pull($key)`.

```php
    $arr->delete('profissao') 
  
```

#### Limpando um array com 'clear'

##### <a name="clear">clear()</a> 

```php
    $arr->clear();
    // ou
    $arr->delete();
```

### <a name="change-advanced-add">Adicionando de forma Avançada</a>

##### <a name="set">set()</a> 

Podemos adicionar um array utilizando o modo avançado com "Dot Notation" com o método `set`.

```php
    $arr->set(
        [
            "access.user.5",
            "user.name.Fernando dos Santos",
            "user.age.25",
            "user.coord..'-18.3952438'",
            "user.coord..'-43.5124499'",
            "user.salario.'R$ 5.526,35'",
            "user.phone.\"38\" 3452-4525",
            "access.history.2014-03-01 18:03:02.Password Error",
            "access.history.2014-03-02 00:31:52.User Error",
        ]);

    // o mesmo que:

    $set = [
        'access' => [
            'user' => 5
            'history' => [
                '2014-03-01 18:03:02' => 'Password Error',
                '2014-03-02 00:31:52' => 'User Error'
            ]
        ],
        'user' => [
                'name' => 'Fernando dos Santos',
                'age' => ['25'],                
                'coord' => [-18.3952438, -43.5124499],
                'salario' => 'R$ 5.526,35',
                'phone' => '"38" 3452-4525'
            ],
    ];

```
| syntax                          | description                                                             | 
|---------------------------------|-------------------------------------------------------------------------| 
| "access.user.5"                 | O último item é um valor                                                | 
| "user.name.Fernando dos Santos" | O valor pode conter espaços                                         | 
| "user.age.'25'"                 | O número pode ser um string adicionando ''                              | 
| "user.coord..'-18.3952438'"     | Adicionando pontos extras cria-se uma nova dimensão e um valor sem chave | 
| "user.phone.\\"38\\" 3452-4525"   | Utilize \ para incluir aspas duplas                                     | 





## <a name="get-info">Acessando Informações</a>

### Pegando a primeira chave

##### <a name="firstKey">firstKey()</a> 

```php
    $arr->firstKey();
    // 'id'
```
 
### Pegando o primero valor

##### <a name="first">first()</a> 

```php
    $arr->first();
    // 32
```
 
### Pegando a última chave

##### <a name="lastKey">lastKey()</a> 

```php
    $arr->lastKey();
    // 'contry'
```
 
### Pegando o último valor

##### <a name="last">last()</a> 

```php
    $arr->last();
    // 'Brasil'
```
  
### <a name="size">Tamanho do Array</a>

##### <a name="count">count()</a> 

```php
    $arr->count();
    // 8
```

Tamanho de um array interno.

```php
    $arr->count('name');
    // 1
```

## <a name="array-check">Verificando um Array</a>


### Checando se um array existe

##### <a name="has">has()</a> 

Devemos utilizar o método `has` neste caso, informando a chave.

```php
    $arr->has('user');
    //true
```
### Checa se um array está vazio

##### <a name="empty">empty()</a> 

Verifica se o array da chave informada, ou todo o array está vazio.

```php
    $arr->empty('user'); //false
```

### Checa se um array é associativo

##### <a name="isAssoc">isAssoc()</a> 

```php
    $arr->isAssoc('user'); //true
```

### Checa se um array de indices

##### <a name="isIndexed">isIndexed()</a> 

```php
    $arr->isIndexed('user'); //false
```

### Checa se um array é multidimensional (possui 2 ou mais dimenssões)

##### <a name="isMulti">isMulti()</a> 

```php
    $arr->isMulti('user'); //true
```

##### <a name="getMaxDimensions">getMaxDimensions()</a> 

```php

$array = ["name" => "Robert", "meio" => ["Machado", "Santos"], "sobrenome" => "de Jesus"];

$arr->add('getUser', $array);

$arr->getMaxDimensions('getUser');

//Resultado: 2


```
 


## <a name="order">Ordenando um Array</a>
 

##### <a name="orderByKey">orderByKey()</a> 

Ordena um array pelas chaves baseadas em um outro array

```php
$array = [
    "index.php" => ['name' => 'index', 'extension' => 'php'],
    "home.php" => ['name' => 'home', 'extension' => 'php'],
    "class.css" => ['name' => 'class', 'extension' => 'css'],
    "script.js" => ['name' => 'script', 'extension' => 'js'],
    "exit.php" => ['name' => 'exit', 'extension' => 'php'],
];


$arr::make($array);


print_r($arr->orderByKey('files', ['script.js','class.css']));

//Array
//(
//    [script.js] => Array
//        (
//            [name] => script
//            [extension] => js
//        )
//    [class.css] => Array
//        (
//            [name] => class
//            [extension] => css
//        )
//    [exit.php] => Array
//        (
//            [name] => exit
//            [extension] => php
//        )
//    [home.php] => Array
//        (
//            [name] => home
//            [extension] => php
//        
//    [index.php] => Array
//        (
//            [name] => index
//            [extension] => php
//        )
//)

print_r($arr->orderByKey('files', ['script.js','class.css'], \Simpla\Arr\Arr::SORT_REVERSE));

//Array
//(
//    [index.php] => Array
//        (
//            [name] => index
//            [extension] => php
//        )
//    [home.php] => Array
//        (
//            [name] => home
//            [extension] => php
//        )
//    [exit.php] => Array
//        (
//            [name] => exit
//            [extension] => php
//        )
//    [class.css] => Array
//        (
//            [name] => class
//            [extension] => css
//        )
//    [script.js] => Array
//        (
//            [name] => script
//            [extension] => js
//        )
//)

```


## <a name="search-array">Pesquisando em um Array</a>



##### <a name="inArray">inArray()</a> 

Encontra recursivamente um valor num array multidimensional

```php

$array = [
           1 => ['framework', "simpla"],
           'info' => [
                'tecnology' => ["php","css","html"]
            ]
        ];

$arr::make($array);

$arr->inArray('css'); //true
$arr->inArray('simpla','simpla.1'); //true
$arr->inArray('java', 'info.tecnology'); //false
$arr->inArray('html', 'info'); //true

```

##### <a name="findKey">findKey()</a> 
Busca recursivamenteum elemento dentro do array pela chave

```php

print_r($arr->findKey('tecnology'));

// Array
// (
//    [0] => php
//    [1] => css
//    [2] => html
//)

print_r($arr->findKey('tecnology', Simpla\Arr\Arr::ARR_PRESERVE_KEY));

//Array
//(
//    [tecnology] => Array
//        (
//            [0] => php
//            [1] => css
//            [2] => html
//        )
//
//)

```


## <a name="transform-array">Transformando um Array</a>

Podemos transformar um array, alterando o seu valor definitivamente ou apenas na saída.


### <a name="transform-array">Alterando a saída Array</a>

Aqui retornamos um array com as alterações realizadas

#### Alterando caixa da fonte

##### <a name="keyCaseToUpper">keyCaseToUpper()</a> 

Podemos alterar para Maiúsculo:

```php
$arr->keyCaseToUpper("user");

Array
//(
//    [NAME] => Fernando dos Santos
//    [AGE] => 25
//    [COORD] => Array
//        (
//            [0] => -18.3952438
//            [1] => -43.5124499
//        )
//
//    [SALARIO] => R$ 5.526,35
//    [PHONE] => "38" 3452-4525
//)
```
##### <a name="keyCaseToLower">keyCaseToLower()</a> 

ou minúsculo:

```php

    $arr->keyCaseToLower("user");
```

##### <a name="mapKey">mapKey()</a> 
Aplica uma função em todos os elementos de uma matriz utilizando também sua chave.

```php

    $array = ['class'=> 'access','id'=>'name'];

    $arr->add("map", $array);
    
    $res = $arr->mapKey("map", function ($k, $v){
          return $k.'="'.htmlspecialchars($v).'"';
    });

//Array
//    (
//        [0] => class="acesso"
//        [1] => id="name"
//    );

```


### <a name="transform-array">Alterando o Array</a>



## <a name="special-array">Operações especiais com um array</a>

##### <a name="toAssoc">toAssoc()</a> 

Transforma um array não associativo em array associativo desde que a quantidade de elementos seja par.

```php

$array = ['Nome',"Robert", "Sobrenome","De Jesus"];
$arr::make($array);
 
$arr->toAssoc();
 
// array(
//        ["Nome"] => "Robert", 
//        ["Sobrenome"] => "De Jesus"
//     ) 

```

##### <a name="assocToKey">assocToKey()</a> 

Transforma um array multidimensional em um array com key=>val.
 
```php

$people = [
    [
        "name" => "Jack",
        "age" => 21
    ],
    [
        "name" => "Jill",
        "age" => 23
    ]
];

$arr::make($array);
$arr->assocToKey("name", "age");

//Array
//(
//    ["Jack"] => 21
//    ["Jill"] => 23
//)
 
```


##### <a name="keyToAssoc">keyToAssoc()</a> 

Transforma um array com key=>val em um array multidimensional associativo com os nomes dos campos fornecidos.

```php

$people = [
    "Jack" => 21,
    "Jill" => 23
];

$arr::make($people);
$arr->keyToAssoc("name", "age");

//(
//    [0] => Array
//    (
//        ["name"] => "Jack"
//        ["age"] => 21
//    )
//    [1] => Array
//    (
//        ["name"] => "Jill"
//        ["age"] => 23
//    )
//)

```


##### <a name="reverseAssoc">reverseAssoc()</a> 

Inverte as chaves de um array multidimensional associativo


```php

$array = [
  "name" => ["arquivo 1", "arquivo 2", ] , 
  "type" => ["php","html"] ,
  "size" => ["14k", "10k"] 
];

$arr::make($array);

// Transforma o array associativo em array de índice
$arr->reverseAssoc(); // ou $arr->reverseAssoc('files');

//Array
//(
//    [0] => Array
//        (
//            [name] => arquivo 1
//            [type] => php
//            [size] => 14k
//        )
//
//    [1] => Array
//        (
//            [name] => arquivo 2
//            [type] => html
//            [size] => 10k
//        ) 
//)

// ATRIBUINDO NOVOS VALORES PARA AS CHAVES:
$arr->reverseAssoc(['Nome','tipo','tamanho']); // ou $arr->reverseAssoc('files', ['Nome','tipo','tamanho']);

//Array
//(
//    [0] => Array
//        (
//            [Nome] => arquivo 1
//            [tipo] => php
//            [tamanho] => 14k
//        )
//
//    [1] => Array
//        (
//            [Nome] => arquivo 2
//            [tipo] => html
//            [tamanho] => 10k
//        ) 
//)

// Transforma um array de índice em associativo  

$array2 = [
    0 => ["name" => "Robert", "meio" => "Diego", "sobrenome" => "de Jesus"],
    1 => ["name" => "Carlos", "meio" => "Henrique", "sobrenome" => "Santos"]
];


$arr::make($array2);
$arr->reverseAssoc(); // ou $arr->reverseAssoc('names');

//Array
//(
//    [name] => Array
//        (
//            [0] => Robert
//            [1] => Carlos
//        ) 
//    [meio] => Array
//        (
//            [0] => Diego
//            [1] => Henrique
//        ) 
//    [sobrenome] => Array
//        (
//            [0] => de Jesus
//            [1] => Santos
//        )
//)

```


