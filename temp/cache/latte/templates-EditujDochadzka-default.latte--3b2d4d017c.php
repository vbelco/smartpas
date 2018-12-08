<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/EditujDochadzka/default.latte

use Latte\Runtime as LR;

class Template3b2d4d017c extends Latte\Runtime\Template
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
?>

<?php
		if ($user->loggedIn) {
?>    
<div>
    <ul class="nav nav-pills">
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.current")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:prehlad")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.prehlad")) ?></a></li>
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("EditujDochadzka:")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.edituj")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Dochadzka:nastavenie")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.section_dochadzka.settings")) ?></a></li>
    </ul>
</div>
    
<div class="container">
    <div class="panel panel-default"> 
    <div class="panel-body">
    <?php
			/* line 25 */
			echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["prehladForm"], []);
?>

        <div class="row required">
            <div class="col-xs-1 required"> <?php if ($_label = end($this->global->formsStack)["datum_od"]->getLabel()) echo $_label ?></div>
            <div class="col-sm-4 input-group date required" id="form-date-od" >
                        <?php echo end($this->global->formsStack)["datum_od"]->getControl() /* line 29 */ ?>

                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <div class="col-xs-1 required"> <?php if ($_label = end($this->global->formsStack)["datum_do"]->getLabel()) echo $_label ?></div>
            <div class="col-sm-4 input-group date required" id ="form-date-do">
                        <?php echo end($this->global->formsStack)["datum_do"]->getControl() /* line 35 */ ?>

                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div> 
        <div class="row">
            <div class="col-sm-1"> <?php if ($_label = end($this->global->formsStack)["osoba"]->getLabel()) echo $_label ?></div>
            <div class="col-sm-4"> <?php echo end($this->global->formsStack)["osoba"]->getControl() /* line 42 */ ?></div>
        </div>
        <div class="my_last_row">
            <div class="col-sm-1"> </div>
            <div class="col-sm-4"><?php echo end($this->global->formsStack)["send"]->getControl() /* line 46 */ ?></div>
        </div>
    <?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
?>

    </div>
    </div>
</div>   
    

<script type="text/javascript">
    $('#form-date-od').datetimepicker({
        minView: 2,
        autoclose: 1,
        todayBtn:  1,
        useCurrent: true,
        format: "dd.mm.yyyy",
        language:  <?php echo LR\Filters::escapeJs($activeLocale) /* line 61 */ ?>

    });
    $('#form-date-do').datetimepicker({
        minView: 2,
        autoclose: 1,
        todayBtn:  1,
        format: "dd.mm.yyyy",
        language:  <?php echo LR\Filters::escapeJs($activeLocale) /* line 68 */ ?>

    });
</script> 

<?php
		}
?> 
<?php
	}

}
