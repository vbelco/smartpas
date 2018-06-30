<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/Osoby/deaktivuj.latte

use Latte\Runtime as LR;

class Templatef78fcc00e7 extends Latte\Runtime\Template
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
		if (isset($this->params['polozka'])) trigger_error('Variable $polozka overwritten in foreach on line 27');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockBanner($_args)
	{
		
	}


	function blockTitle($_args)
	{
		extract($_args);
		?>    <h1><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.person_deactivation")) ?></h1>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
		if ($user->loggedIn) {
?>    
<div>
    <ul class="nav nav-pills">
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Osoby:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_osoby.prehlad")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Osoby:pridaj")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_osoby.pridaj")) ?></a></li>
    </ul>
</div>
    
<div>
<?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.really_deactivate_this_person")) ?>: <?php
			echo LR\Filters::escapeHtmlText($meno) /* line 21 */ ?> ?
</div>
<?php
			if ($rfidky) {
?>
    <div>
    <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.this_person_has_assigned_rfid")) ?>:
    <ul>
<?php
				$iterations = 0;
				foreach ($rfidky as $polozka) {
					?>        <li> <?php echo LR\Filters::escapeHtmlText($polozka->number) /* line 28 */ ?> </li>
<?php
					$iterations++;
				}
?>
    </ul>
    <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.for_person_deactivation_needs_be_rfid_cleaned")) ?>.
    </div>

<?php
			}
			else {
?>
    <div>
    <a  href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Osoby:deaktivuj", [$osoba_id , 'proceed' => 1])) ?>"><button type="button" class="btn btn-danger"><?php
				echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.form.deactivate")) ?></button></a>
    </div>  
<?php
			}
?>

<?php
		}
?> 
<?php
	}

}
