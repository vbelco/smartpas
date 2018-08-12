<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/RFID/default.latte

use Latte\Runtime as LR;

class Template25ca343b3b extends Latte\Runtime\Template
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
		if (isset($this->params['post'])) trigger_error('Variable $post overwritten in foreach on line 28');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockBanner($_args)
	{
		
	}


	function blockTitle($_args)
	{
		extract($_args);
		?>    <h1><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.rfid_management")) ?></h1>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
		if ($user->loggedIn) {
?>    
<div>
    <ul class="nav nav-pills">
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("RFID:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.citacka.prehlad")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("RFID:pridaj")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_rfid.add")) ?></a></li>
    </ul>
</div>

<div class="table-responsive">
<table class=" table table-hover">
    <tr>
        <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.id_rfid")) ?></td>
        <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.assigned_person")) ?></td>
        <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.action")) ?></td>
    </tr>
<?php
			$iterations = 0;
			foreach ($posts as $post) {
?>
            <tr>
                <td><?php echo LR\Filters::escapeHtmlText($post->number) /* line 30 */ ?></td>
                <td>
                    <?php
				if (($post->people_id != NULL)) {
					?>                        <?php echo LR\Filters::escapeHtmlText($post->people->meno) /* line 33 */ ?>

                        <a class="btn btn-default" role="button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("priradOsobu", [$post->id])) ?>"><?php
					echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.form.change")) ?></a>
<?php
				}
				else {
					?>                        <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.not_assigned")) ?>

                        <a class="btn btn-default" role="button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("priradOsobu", [$post->id])) ?>"><?php
					echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.form.assign")) ?></a>
<?php
				}
?>
                </td>
                <td>
                    <a class="btn btn-default" role="button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("edituj", [$post->id])) ?>"><?php
				echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.form.edit")) ?></a> 
                </td>     
            </tr>
<?php
				$iterations++;
			}
?>
</table>
</div>
<?php
		}
?> 
<?php
	}

}
