<?php
	/**
	 * Created by PhpStorm.
	 * User: art
	 * Date: 3/21/2018
	 * Time: 2:55 PM
	 */

	namespace app\models;


	class State
	{
		const DONE = 2;
		const IN_PROGRESS = 1;
		const TODO = 0;

		public static $instance;
		private static $states = [
			'Todo',
			'In progress',
			'Done',
		];
		public $id;
		public $label;
		public $finished = false;
		public $color = '#000000';

		private function __construct($state)
		{
			switch ($state){
				case 0:
					$this->id = $state;
					$this->label = self::$states[$state];
					$this->finished = false;
					$this->color = '#5bc0de';
					return $this;
					break;
				case 1:
					$this->id = $state;
					$this->label = self::$states[$state];
					$this->finished = false;
					$this->color = '#f0ad4e';
					return $this;
					break;
				case 2:
					$this->id = $state;
					$this->label = self::$states[$state];
					$this->finished = true;
					$this->color = '#5cb85c';
					return $this;
					break;
				default :
					return $this;
					break;
			}
		}

		public static function getState($state = false):State
		{
			static::$instance = new self($state);
			return static::$instance;
		}

		public static function getStates()
		{
			return self::$states;
		}

	}