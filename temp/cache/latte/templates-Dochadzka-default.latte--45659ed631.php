<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/Dochadzka/default.latte

use Latte\Runtime as LR;

class Template45659ed631 extends Latte\Runtime\Template
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
		if (isset($this->params['post'])) trigger_error('Variable $post overwritten in foreach on line 30');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockBanner($_args)
	{
		
	}


	function blockTitle($_args)
	{
		extract($_args);
		?>    <h1><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.dochadzka")) ?></h1>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
		if ($user->loggedIn) {
?>    
<div>
    <ul class="nav nav-pills">
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.current")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:prehlad")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.prehlad")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:nastavenie")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.settings")) ?></a></li>
    </ul>
</div>

    <h2><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.people_at_work")) ?></h2>
    
<div class="table-responsive">
<table class=" table table-hover">
    <tr>
        <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.name")) ?></th>
        <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.prichod")) ?></th>
    </tr>

<?php
			$iterations = 0;
			foreach ($posts as $post) {
?>
            <tr>
                <td><?php echo LR\Filters::escapeHtmlText($post->meno) /* line 32 */ ?></td>                
                <td><?php echo LR\Filters::escapeHtmlText($post->prichod_timestamp) /* line 33 */ ?></td>     
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
