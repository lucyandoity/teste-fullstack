<?php
App::uses('Provider', 'Model');

/**
 * Provider Test Case
 *
 * Testes unitários para o Model Provider (Prestador).
 *
 * @package app.Test.Case.Model
 */
class ProviderTest extends CakeTestCase {

/**
 * Fixtures utilizados nos testes
 *
 * @var array
 */
    public $fixtures = array(
        'app.provider',
        'app.service'
    );

/**
 * Instância do Model
 *
 * @var Provider
 */
    public $Provider;

/**
 * Configuração antes de cada teste
 *
 * @return void
 */
    public function setUp() {
        parent::setUp();
        $this->Provider = ClassRegistry::init('Provider');
    }

/**
 * Limpeza após cada teste
 *
 * @return void
 */
    public function tearDown() {
        unset($this->Provider);
        parent::tearDown();
    }

/**
 * Testa validação do campo nome - obrigatório
 *
 * @return void
 */
    public function testNameIsRequired() {
        $data = array(
            'Provider' => array(
                'name' => '',
                'email' => 'teste@email.com',
                'phone' => '(11) 99999-9999'
            )
        );

        $this->Provider->set($data);
        $this->assertFalse($this->Provider->validates());
        $this->assertArrayHasKey('name', $this->Provider->validationErrors);
    }

/**
 * Testa validação do campo nome - tamanho mínimo
 *
 * @return void
 */
    public function testNameMinLength() {
        $data = array(
            'Provider' => array(
                'name' => 'AB',
                'email' => 'teste@email.com',
                'phone' => '(11) 99999-9999'
            )
        );

        $this->Provider->set($data);
        $this->assertFalse($this->Provider->validates());
        $this->assertArrayHasKey('name', $this->Provider->validationErrors);
    }

/**
 * Testa validação do campo email - formato inválido
 *
 * @return void
 */
    public function testEmailInvalidFormat() {
        $data = array(
            'Provider' => array(
                'name' => 'Teste Prestador',
                'email' => 'email-invalido',
                'phone' => '(11) 99999-9999'
            )
        );

        $this->Provider->set($data);
        $this->assertFalse($this->Provider->validates());
        $this->assertArrayHasKey('email', $this->Provider->validationErrors);
    }

/**
 * Testa validação do campo email - unicidade
 *
 * @return void
 */
    public function testEmailMustBeUnique() {
        $data = array(
            'Provider' => array(
                'name' => 'Novo Prestador',
                'email' => 'joao.silva@email.com', // Email já existe
                'phone' => '(11) 99999-9999'
            )
        );

        $this->Provider->set($data);
        $this->assertFalse($this->Provider->validates());
        $this->assertArrayHasKey('email', $this->Provider->validationErrors);
    }

/**
 * Testa validação do campo telefone - formato inválido
 *
 * @return void
 */
    public function testPhoneInvalidFormat() {
        $data = array(
            'Provider' => array(
                'name' => 'Teste Prestador',
                'email' => 'novo@email.com',
                'phone' => '123456'
            )
        );

        $this->Provider->set($data);
        $this->assertFalse($this->Provider->validates());
        $this->assertArrayHasKey('phone', $this->Provider->validationErrors);
    }

/**
 * Testa validação com todos os campos válidos
 *
 * @return void
 */
    public function testValidProvider() {
        $data = array(
            'Provider' => array(
                'name' => 'Prestador Válido',
                'email' => 'prestador.valido@email.com',
                'phone' => '(11) 99999-8888'
            )
        );

        $this->Provider->set($data);
        $this->assertTrue($this->Provider->validates());
    }

/**
 * Testa se o email é normalizado para minúsculas
 *
 * @return void
 */
    public function testEmailNormalization() {
        $data = array(
            'Provider' => array(
                'name' => 'Teste Normalização',
                'email' => 'TESTE@EMAIL.COM',
                'phone' => '(11) 99999-7777'
            )
        );

        $this->Provider->save($data);
        $saved = $this->Provider->read(null, $this->Provider->id);

        $this->assertEquals('teste@email.com', $saved['Provider']['email']);
    }

/**
 * Testa formatação do telefone
 *
 * @return void
 */
    public function testPhoneFormatting() {
        $data = array(
            'Provider' => array(
                'name' => 'Teste Telefone',
                'email' => 'telefone@email.com',
                'phone' => '11999996666'
            )
        );

        $this->Provider->save($data);
        $saved = $this->Provider->read(null, $this->Provider->id);

        $this->assertEquals('(11) 99999-6666', $saved['Provider']['phone']);
    }

/**
 * Testa relacionamento hasMany com Service
 *
 * @return void
 */
    public function testHasManyServices() {
        $provider = $this->Provider->find('first', array(
            'conditions' => array('Provider.id' => 1),
            'contain' => array('Service')
        ));

        $this->assertArrayHasKey('Service', $provider);
        $this->assertNotEmpty($provider['Service']);
    }

/**
 * Testa exclusão em cascata dos serviços
 *
 * @return void
 */
    public function testCascadeDeleteServices() {
        // Conta serviços antes
        $Service = ClassRegistry::init('Service');
        $countBefore = $Service->find('count', array(
            'conditions' => array('Service.provider_id' => 1)
        ));
        $this->assertGreaterThan(0, $countBefore);

        // Exclui o prestador
        $this->Provider->delete(1);

        // Conta serviços depois
        $countAfter = $Service->find('count', array(
            'conditions' => array('Service.provider_id' => 1)
        ));
        $this->assertEquals(0, $countAfter);
    }
}
