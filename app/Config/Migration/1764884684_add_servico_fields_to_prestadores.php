<?php
class AddServicoFieldsToPrestadores extends CakeMigration
{

	/**
	 * Migration description
	 *
	 * @var string
	 */
	public $description = 'add_servico_fields_to_prestadores';

	/**
	 * Actions to be performed
	 *
	 * @var array $migration
	 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'prestadores' => array(
					'servico_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'after' => 'email'),
					'valor_servico' => array('type' => 'decimal', 'length' => '10,2', 'null' => true, 'default' => null, 'after' => 'servico_id'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'prestadores' => array('servico_id', 'valor_servico'),
			),
		),
	);

	/**
	 * Before migration callback
	 *
	 * @param string $direction Direction of migration process (up or down)
	 * @return bool Should process continue
	 */
	public function before($direction)
	{
		return true;
	}

	/**
	 * After migration callback
	 *
	 * @param string $direction Direction of migration process (up or down)
	 * @return bool Should process continue
	 */
	public function after($direction)
	{
		return true;
	}
}
