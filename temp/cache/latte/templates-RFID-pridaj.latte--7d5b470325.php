<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/RFID/pridaj.latte

use Latte\Runtime as LR;

class Template7d5b470325 extends Latte\Runtime\Template
{
	public $blocks = [
		'banner' => 'blockBanner',
		'title' => 'blockTitle',
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'banner' => 'html',
		'title' => 'html',
		'content' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('banner', get_defined_vars());
?>

<?php
		$this->renderBlock('title', get_defined_vars());
?>


<?php
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['novy'])) trigger_error('Variable $novy overwritten in foreach on line 42');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockBanner($_args)
	{
		
	}


	function blockTitle($_args)
	{
		extract($_args);
		?>    <h1><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.add_rfid")) ?></h1>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
		if ($user->loggedIn) {
?>    
<div>
    <ul class="nav nav-pills">
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("RFID:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_rfid.prehlad")) ?></a></li>
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("RFID:pridaj")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_rfid.add")) ?></a></li>
    </ul>
</div>
<div>
    <h3><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.manual_add_rfid")) ?></h3>    
<?php
			/* line 21 */ $_tmp = $this->global->uiControl->getComponent("rFIDForm");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
			$_tmp->render();
?>
</div>    
<div>
    <h3><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.auto_add_rfid")) ?></h3>
    <p><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.auto_add_rfid_explain")) ?> </p>
<?php
			/* line 26 */ $_tmp = $this->global->uiControl->getComponent("zvolCitackuForm");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
			$_tmp->render();
			if (isset($zvol_citacku_form_submitted)) {
				?>    <p><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.scan_from_reader")) ?>: <?php
				echo LR\Filters::escapeHtmlText($cislo_citacky) /* line 28 */ ?></p>
    <p><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.scan_time")) ?>: <?php
				echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $timestamp, '%d.%m.%Y %H:%M:%S')) /* line 29 */ ?></p>
    <p><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.than_press_refresh")) ?> </p>
    <div>
    <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Obnov!", [$cislo_citacky, $timestamp])) ?>"><button type="button" class="btn btn-danger"><?php
				echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.form.refresh")) ?></button></a>
    </div> 
<?php
			}
			if (isset($obnovene)) {
				?>        <p><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.scan_from_reader")) ?>: <?php
				echo LR\Filters::escapeHtmlText($obnovene) /* line 36 */ ?>...</p>
        <p><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.scan_time")) ?>: <?php
				echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $timestamp, '%d.%m.%Y %H:%M:%S')) /* line 37 */ ?></p>
        <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Obnov!", [$obnovene, $timestamp])) ?>"><button type="button" class="btn btn-danger"><?php
				echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.form.refresh_again")) ?></button></a>
        <div class="table-responsive">
        <table class="table table-hover table-bordered">
<?php
				if (isset($nove)) {
					$iterations = 0;
					foreach ($nove as $novy) {
?>
            <tr>
                <td><?php echo LR\Filters::escapeHtmlText($novy->id) /* line 44 */ ?></td> 
                <td><?php echo LR\Filters::escapeHtmlText($novy->rfid_number) /* line 45 */ ?></td> 
                <td><?php echo LR\Filters::escapeHtmlText($novy->timestamp) /* line 46 */ ?></td>
                <td><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("PridajNovu!", [$novy->rfid_number, $obnovene, $timestamp, $novy->id])) ?>"><button type="button" class="btn btn-danger"><?php
						echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.add_to_app")) ?></button></a></td>
            </tr>
<?php
						$iterations++;
					}
				}
?>
        </table>
        </div>
<?php
			}
?>
</div>
<?php
		}
		
	}

}
