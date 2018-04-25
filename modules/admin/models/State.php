<?php
	/**
	 * Created by PhpStorm.
	 * User: art
	 * Date: 3/21/2018
	 * Time: 2:55 PM
	 */

	namespace app\modules\admin\models;


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
		public $class = '#000000';

		private function __construct($state)
		{
			switch ($state){
				case self::TODO:
					$this->id = $state;
					$this->label = self::$states[$state];
					$this->finished = false;
					$this->class = 'tab-todo';
					return $this;
					break;
				case self::IN_PROGRESS:
					$this->id = $state;
					$this->label = self::$states[$state];
					$this->finished = false;
					$this->class = 'tab-in-progress';
					return $this;
					break;
				case self::DONE:
					$this->id = $state;
					$this->label = self::$states[$state];
					$this->finished = true;
					$this->class = 'tab-done';
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