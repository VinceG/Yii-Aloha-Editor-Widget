Yii-RedactorJS-Widget
=====================

Yii Widget for the WYSIWYG RedactorJS editor

- [Project Page](http://aloha-editor.org/)
- [Examples](http://aloha-editor.org/demos.php)
- [Documentation](http://aloha-editor.org/builds/development/latest/doc/guides/output/using_aloha.html)

Requirements
=====================

- JQuery > 1.7.1
- Browser:
 - Firefox 3+
 - Safari 4+
 - Chrome 4+
 - Opera 10+
 - IE 7+

Installation
=====================

1. Download or Clone the files
2. Extract into the widgets folder or extensions folder

Usage
===================

Using with a model
------------------

~~~
$this->widget('application.widgets.alohaeditor.AlohaEditor', array( 'model' => $model, 'attribute' => 'some_attribute', 'showTextarea' => true ));
~~~

- By default 'showTextarea' is set to false

Using selector to set multiple elements editable
------------------

~~~
$this->widget('application.widgets.alohaeditor.AlohaEditor', array( 'selector' => '.editable' ));
~~~


Using with a model and a basic toolbar
------------------

~~~
$this->widget('application.widgets.alohaeditor.AlohaEditor', array('toolbar' => 'basic', 'model' => $model, 'attribute' => 'some_attribute' ));
~~~

- There are two toolbars supported right now: basic and advanced
- At any point you can add more plugins to the toolbar by assigned array elements to the plugins property in the widget

Using with a model and a basic toolbar and custom plugins
------------------

~~~
$this->widget('application.widgets.alohaeditor.AlohaEditor', array('toolbar' => 'basic', 'plugins' => array('extra/hints'), 'model' => $model, 'attribute' => 'some_attribute' ));
~~~

Using with a model and a basic toolbar and custom editor settings
------------------

~~~
$this->widget('application.widgets.alohaeditor.AlohaEditor', array('alohaSettings' => array('lang' => 'fr'), 'model' => $model, 'attribute' => 'some_attribute' ));
~~~

- Supported languages currently: de, en, fr, lv, pl, pt_br, ru, ua
- Refer to the documentation for a list of supported params for the settings array

Using with a name and value
------------------

~~~
$this->widget('application.widgets.alohaeditor.AlohaEditor', array( 'name' => 'some name', 'value' => 'some value' ));
~~~


Authors
==================

Vincent Gabriel <http://vadimg.com>