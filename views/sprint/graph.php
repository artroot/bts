<?php
	/**
	 * Created by PhpStorm.
	 * User: art
	 * Date: 2/23/2018
	 * Time: 8:40 PM
	 */
	use app\components\SVG;

?>
<div class="graphic">
<div class="scaleY">
	<div>
		<?php for($i = $scales[SVG::SVG_Y]['max']; $i >= 0; $i -= $scales[SVG::SVG_Y]['step']): ?>
			<div style="bottom: <?= $i*100/$scales[SVG::SVG_Y]['max'] ?>%;"><?= $i ?></div>
		<?php endfor; ?>
	</div>
</div>
<svg width="97%" height="97%" xmlns="http://www.w3.org/2000/svg">
	<g>
		<line stroke-linecap="undefined" stroke-linejoin="undefined" id="svg_g_g"
			  y2="0%"
			  x2="0%"
			  y1="100%"
			  x1="0%" stroke-width="1" stroke="#cccccc" fill="none"/>
	</g>
	<g>
		<?php foreach($scales[SVG::SVG_Y]['coords'] as $id => $coord): ?>
			<line stroke-linecap="undefined" stroke-linejoin="undefined" id="svg_<?= SVG::SVG_Y ?>_<?= $id ?>"
				  y2="<?= $coord['y2'] ?>"
				  x2="<?= $coord['x2'] ?>"
				  y1="<?= $coord['y1'] ?>"
				  x1="<?= $coord['x1'] ?>" stroke-width="1" stroke="#cccccc" fill="none"/>
		<?php endforeach; ?>
	</g>
	<g>
		<?php foreach($scales[SVG::SVG_X]['coords'] as $id => $coord): ?>
			<line stroke-linecap="undefined" stroke-linejoin="undefined" id="svg_<?= SVG::SVG_X ?>_<?= $id ?>"
				  y2="<?= $coord['y2'] ?>"
				  x2="<?= $coord['x2'] ?>"
				  y1="<?= $coord['y1'] ?>"
				  x1="<?= $coord['x1'] ?>" stroke-width="1" stroke="#cccccc" fill="none"/>
		<?php endforeach; ?>
	</g>
	<?php foreach ($graphs as $coords): ?>
		<g>
			<?php foreach($coords as $id => $coord): ?>
				<line stroke-linecap="undefined" stroke-linejoin="undefined" id="svg_<?= SVG::SVG_X ?>_<?= $id ?>"
					  y2="<?= $coord['line']['y2'] ?>"
					  x2="<?= $coord['line']['x2'] ?>"
					  y1="<?= $coord['line']['y1'] ?>"
					  x1="<?= $coord['line']['x1'] ?>"
					  stroke-width="<?= $coord['line']['stroke-width'] ?>"
					  stroke="<?= $coord['line']['stroke'] ?>" fill="none"/>
				<?php if(isset($coord['ellipse'])): ?>
				<ellipse fill="<?= $coord['ellipse']['fill'] ?>"
						 stroke="<?= $coord['ellipse']['stroke'] ?>"
						 stroke-width="<?= $coord['ellipse']['stroke-width'] ?>"
						 stroke-opacity="null" style="pointer-events:inherit"
						 cx="<?= $coord['ellipse']['cx'] ?>"
						 cy="<?= $coord['ellipse']['cy'] ?>" id="svg_el_<?= $id ?>"
						 rx="<?= $coord['ellipse']['ry'] ?>"
						 ry="<?= $coord['ellipse']['rx'] ?>" stroke-dasharray="none"></ellipse>
				<?php endif; ?>
			<?php endforeach; ?>
		</g>
	<?php endforeach; ?>
</svg>

<div class="scaleX">
	<div>
		<?php for($i = 0; $i <= count($scales[SVG::SVG_X]['coords']); $i++): ?>
			<div style="left: <?= $i*100/count($scales[SVG::SVG_X]['coords']) ?>%;"><?= $i ?></div>
		<?php endfor; ?>
	</div>
</div>
</div>