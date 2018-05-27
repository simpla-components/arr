<?php
 
namespace Simpla\Arr;

use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
    public $arr;
    
    public $simple = [1,2,3,4,5];
    public $order = [11,8,3,4,6,32,2,1];
    public $flip = ['foo', 'bar'];
    public $num = [1,2,3,4,4,5, 5.5,6,7,8,8,9,10,19.3];
    public $names = ['João','maria','pedro','felipe','carlos','Ricardo','ANGÉLICA'];
    public $mixNumNames = [1,2,'árvore','folha',5, 5, 'folha','flor','rio',12];
    public $keyName = ['name' => 'Fernando', 'idade' => 21, 'address' => 'Rua Direita,43', 'profissao' => 'Padeiro'];
    public $newKeyName = ['address' => 'Rua Direita,43', 'name' => 'Fernando', 'idade' => 21, 'profissao' => 'Padeiro'];
    public $mult = [
                        0 => ['id' => 32, 'name' => 'Bruno', 'idade' => 43, 'cidade' => 'São Paulo', 'profissão' => 'Médico'],  
                        1 => ['id' => 33, 'name' => 'Ana', 'idade' => 22, 'cidade' => 'São Gonçalo', 'profissão' => 'Enfermeira'],  
                        2 => ['id' => 33, 'name' => 'Cristina', 'idade' => 26, 'cidade' => 'Rio Preto', 'profissão' => 'Agente de saúde'],  
                    ];
    public $multComplex = [
                        'Projeto' => [
                            'nome' => 'Construcao de Escola',
                            'orcamento' => 1234345.43,
                            'responsavel' => [
                                                 'nome' => 'Antonio dos Santos',
                                                 'cargo' => 'Gerente'
                                              ]
                                    ],
                        'Documentos' => [
                                            'nome'=>'orcamento',
                                            'formato' => 'pdf',
                                            'origem' => [
                                                'servidor' => '10.22.535.63',
                                                'login' => ['user' => 'a.santos']
                                            ],
                                    ]
                    ];




    public function setUp()
    {
        $this->arr = new Arr();
    }
     
    
    public function testArrayAccess()
    {
        $this->arr[] = 42; 
        
        $a[] = 42; 
        
        $this->assertEquals($a, $this->arr->get());
    }
    
    public function testInsertArray()
    {
        $this->arr['simple'] = $this->simple; 
        
        $this->assertEquals($this->simple, $this->arr->get('simple'));
        $this->assertEquals(5, $this->arr->count('simple'));
    }
    
    public function testBasicOperationsInSimpleArray()
    {
        // adicionando com ArrayAccess
        $this->arr['name'] = 'Fernando';
        $this->arr['idade'] = 21;
        $this->arr['address'] = 'Rua Direita,43';
        $this->arr['profissao'] = 'Padeiro';
        
        // Pegando com ArrayAccess
        $this->assertEquals($this->keyName['name'], $this->arr['name']);
        
        // Pegando com o método get
        $this->assertEquals($this->keyName['name'], $this->arr->get('name'));
        
        // pegando tudo
        $this->assertEquals($this->keyName, $this->arr->all());
        
        // adiciona no início
        $this->arr->prepend('id', 32);        
        $this->assertEquals('32', $this->arr['id']);
        
        //adicionar no final - o mesmo que push
        $this->arr->add('contry', 'Brasil');
        $this->assertEquals('Brasil', $this->arr->get('contry'));
        
        
        //remove no inicio ou pelo array informado - o mesmo que shift
        $this->assertEquals(['idade' => 21], $this->arr->pull('idade',Arr::ARR_PRESERVE_KEY));
        
        //remove no final ou pelo array informado
        $this->assertEquals('Brasil', $this->arr->pop('contry'));
        
        //remove pela chave
        $this->arr->delete('profissao');
        $this->assertEmpty($this->arr['profissao']);
        
        // inserindo em uma posição 
        $this->arr->insert('sexo', 'M', 2);
        $this->arr->insert('telefone', 35313396, 2);
         
        // pega a primeira chave  
        $this->assertEquals('id', $this->arr->firstKey());
        
        // pegando o primeiro valor
        $this->assertEquals(32, $this->arr->first());
        
        // pegando a ultima chave
        $this->assertEquals('address', $this->arr->lastKey());
        
        // pegando o ultimo valor
        $this->assertEquals('Rua Direita,43', $this->arr->last());
        
        // limpa o array
        $this->arr->clear();
        
        $this->assertEmpty($this->arr->get());
    } 
    
    public function testBasicOperationsInComplexArray()
    {
        // define um novo array, substituindo o existente
       $arr = $this->arr::make($this->multComplex);
       
        // adiciona no inicio
        $arr->prepend('titulo.name', 'O inicio da obra'); 
        $this->assertEquals("O inicio da obra", $arr->get("titulo.name")); 
        
        // adiciona no final
        $arr->add("Projeto.responsavel.dt_admissao", '2012-03-01');
        $this->assertEquals("2012-03-01", $arr->get("Projeto.responsavel.dt_admissao")); 
        
        // substituindo um valor existente
        $arr->add("Projeto.responsavel.nome", 'Pedro dos Santos'); 
        $this->assertEquals("Pedro dos Santos", $arr->get("Projeto.responsavel.nome")); 
        
        //remove do inicio e imprime - o mesmo que pull
        
        $this->assertEquals("Construcao de Escola", $arr->shift('Projeto'));        
        $this->assertEquals(["orcamento" => 1234345.43], $arr->shift('Projeto', Arr::ARR_PRESERVE_KEY));
        
        // remove no fim e imprime
        $this->assertEquals('2012-03-01', $arr->pop('Projeto.responsavel'));
        $this->assertEquals(['cargo' => 'Gerente'], $arr->pop('Projeto.responsavel', Arr::ARR_PRESERVE_KEY));
         
        // ultimo valor pela chave
        $this->assertEquals(['user' => 'a.santos'], $arr->last('Documentos.origem'));
//        print_r($arr->get());
         
    }
    
    
    public function testOperations()
    {
        $arr = $this->arr::make($this->multComplex);
        
        $this->assertJson($arr->toJson());
        
        $this->assertEquals(4, $arr->getMaxDimensions());
        
        $this->assertTrue($arr->isAssoc());
        
        $arr->clear("Documentos");
        $this->assertTrue($arr->isEmpty('Documentos'));
         
        $this->assertEquals(false, $arr->isIndexed('Projeto'));
        
        $this->assertTrue($arr->isMulti('Projeto'));
        $this->assertTrue($arr->isAssoc('Projeto'));
        
        $this->assertTrue($arr->inArrayRecursive('Antonio dos Santos'));
        $this->assertEquals(['nome' => 'Antonio dos Santos', 'cargo' => 'Gerente'],$arr->findKey('responsavel')); //Arr::ARR_PRESERVE_KEY
        
        $this->assertEquals(['nome="Antonio dos Santos"', 'cargo="Gerente"'],
                $arr->mapKey('Projeto.responsavel', function($k, $v){
                    return $k.'="'.htmlspecialchars($v).'"';
                }));
        
    }
    
    public function testOrder()
    {
        $arr = $this->arr::make($this->keyName);        
        $this->assertEquals($this->newKeyName ,$arr->orderByKey(['address']));
         
    }
    
    public function testSet()
    {
        $this->arr->set(["acesso.negado.inclusao",
                         "user.age.'32.1'.'45.6663'.'45.1'", 
                         "user.salario.'R$ 5,425.25'", 
                         "user.home.\"23\" ",
                         "user...Fernando"]);

        $set = [
                'acesso' => ['negado' => 'inclusao'],
                'user' => ['age' => 
                            ['32.1' => ['45.6663' => '45.1']],
                          'salario' => 'R$ 5,425.25',
                          'home' => '"23 ',
                          ['Fernando']
                    ]
                ];
        
        
        
        $this->assertEquals($set, $this->arr->all());
    }
    
    public function testFlip()
    {
        $arr = $this->arr::make($this->flip);        
        $this->assertEquals(['foo' => 0,'bar' => 1], $arr->flip());
    }
    
    public function testChunk()
    {
      $arr = $this->arr::make($this->order);
      
      $get = [[11,8,3],[4,6,32],[2,1]];
      $getPreserve = [[11,8,3],[3=>4,4=>6,5=>32],[6=>2,7=>1]];
       
      $this->assertEquals($get, $arr->chunk(3));
      $this->assertEquals($getPreserve, $arr->chunk(3, Arr::ARR_PRESERVE_KEY));
      
      $name = ['ages' => $this->order];
      $arr2 = $this->arr::make($name); 
      
      $this->assertEquals($getPreserve, $arr2->chunk(3, 'ages', Arr::ARR_PRESERVE_KEY));
    }
    
    public function testUnique()
    {
        $arr = $this->arr::make($this->num);        
        $this->assertEquals(array_unique($this->num), $arr->unique());
    }
    
    
//    public function testColumn()
//    {
//        
//    }
    
// <editor-fold defaultstate="collapsed" desc="changeKeyMethod">
//    public function testChangeKey()
//    {
//        $this->arr->set(['system.user.name.Jose dos Santos','system.user.age.32']);
//        
//        print_r($this->arr->get());
//        
//        $this->arr->changeKey('system.user.name', 'system.admin.name');
//        $this->arr->changeKey('system.user.age', 'system.admin.age');
//        
//        print_r($this->arr->get());
//    }


    /**
     * Computa a diferença entre arrays usando as chaves na comparação indicadas em outro array
     * 
     * @access public
     * @category helper
     * 
     * @param Array $array Array a ser comparado
     * @param mixed $old_key Antigo nome da chave
     * @param mixed $new_key Novo nome da chave
     */

//    public function changeKey($oldKey, $newKey)
//    {
//       $_array = $this->get($oldKey, true); 
//     
//       $this->changeKeyRecursive($oldKey, $newKey, $_array);
//       
//       $this->offsetUnset($oldKey); 
//    }
//    
//    private function changeKeyRecursive($oldKey, $newKey, $_array)
//    {
//       foreach ($_array as $key => $value) { 
//          if (is_array($value)){
//             $_array[$key] = $this->changeKeyRecursive($oldKey, $newKey, $value);
//          }
//          else{
//              $this->offsetSet($newKey, $value);    
//              
//              return ;
//            }
//       }        
//    }// </editor-fold>
    
    
}
