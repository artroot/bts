<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 2/23/2018
 * Time: 7:23 PM
 *
 * @property array $data
 * @property \app\components\SVG $instance
 */

namespace app\components;

use Yii;

final class SVG
{
	const SVG_X = 'X';
	const SVG_Y = 'Y';
	private $data = [];
	private $maxX = 0;
	private $maxY = 0;
	public static $instance;
	private $coords = [];
	private $scales = [];

	private function __construct($data, $maxX, $maxY) {
		$this->data = $data;
		$this->maxX = $maxX ?: count($data);
		$this->maxY = $maxY ?: count($data);
	}

	public static function generate($data = [], $maxX = false, $maxY = false)
	{
		self::$instance = new self($data, $maxX, $maxY);
		return self::$instance->setCoordinates()->setScaleX()->setScaleY();
	}

	private function setScaleY()
	{
		for ($i = 0; $i <= self::getDivide(self::SVG_Y)['max']; $i += self::getDivide(self::SVG_Y)['step']) {
			$this->scales[self::SVG_Y]['coords'][] = [
				'y1' => (($i*100/self::getDivide(self::SVG_Y)['max']) . '%'),
				'y2' => (($i*100/self::getDivide(self::SVG_Y)['max']) . '%'),
				'x1' => '-1%',
				'x2' => '100%',
			];
		}
		return $this;
	}

	private function setScaleX()
	{
		for ($i = 0; $i < $this->maxX; $i++) {
			$this->scales[self::SVG_X]['coords'][] = [
				'y1' => '101%',
				'y2' => '99%',
				'x1' => ($i*100/$this->maxX) . '%',
				'x2' => ($i*100/$this->maxX) . '%',
			];
		}
		return $this;
	}

	private function setCoordinates()
	{
		for ($i = 0; $i < count($this->data); $i++) {
			$this->coords[] = [
				'line' => [
					'y1' => (isset($this->data[$i - 1]) ? 100-($this->data[$i - 1]*100/$this->getDivide(self::SVG_Y)['max']) . '%' : 100-($this->data[$i]*100/$this->getDivide(self::SVG_Y)['max']) . '%'),
					'y2' => 100-($this->data[$i]*100/$this->getDivide(self::SVG_Y)['max']) . '%',
					'x1' => ($i > 0 ? (($i-1)*100/($this->maxX-1)) . '%' : ($i*100/($this->maxX-1)) . '%'),
					'x2' => ($i*100/($this->maxX-1)) . '%',
					'stroke-width' => 1.5,
					'stroke' => 'red'
				],
				'ellipse' => [
					'cx' => ($i*100/($this->maxX-1)) . '%',
					'cy' => 100-($this->data[$i]*100/$this->getDivide(self::SVG_Y)['max']) . '%',
					'rx' => 1.6,
					'ry' => 1.6,
					'stroke-width' => 1.5,
					'stroke' => 'red',
					'fill' => 'red',
				]
			];
		}
		return $this;
	}

	private function getDivide($axis)
	{
		if(!isset($this->scales[$axis])) $this->scales[$axis] = $this->divideToPortion($this->data[0]);
		return $this->scales[$axis];
	}

	/**
	 * @param int $countMax
	 * @return array
	 */
	private function divideToPortion($countMax)
	{
		$step = str_pad(1, strlen($countMax), 0)/2;
		$max = 0;
		do{
			$max += $step;
		}while($max < $countMax);
		$max += $step;
		return ['max' => $max, 'step' => $step];
	}

	/**
	 * @return array
	 */
	public function getCoords()
	{
		return $this->coords;
	}

	/**
	 * @return array
	 */
	public function getScales()
	{
		return $this->scales;
	}

	public function getIdeal()
	{
		$idealCoords[] = [
			'line' => [
				'y1' => ($this->getDivide(self::SVG_Y)['step']*100/$this->getDivide(self::SVG_Y)['max']) . '%',
				'y2' => '100%',
				'x1' => '0%',
				'x2' => '100%',
				'stroke-width' => 1.5,
				'stroke' => 'blue'
			]
		];
		return $idealCoords;
	}

}