var RTOOLBAR = {
	html: { name: 'html', title: RLANG.html, func: 'toggle' },	
	styles: 
	{
		name: 'styles', title: RLANG.styles, func: 'show', 
		dropdown: 
		{
			p: 			{exec: 'formatblock', name: '<p>', title: RLANG.paragraph},
			h1: 		{exec: 'formatblock', name: '<h1>', title: 'Header 1', style: 'font-size: 20px;'},
			h2: 		{exec: 'formatblock', name: '<h2>', title: 'Header 2', style: 'font-size: 18px;'},
			h3: 		{exec: 'formatblock', name: '<h3>', title: 'Header 3', style: 'font-size: 14px; font-weight: bold;'},															
			h4: 		{exec: 'formatblock', name: '<h4>', title: 'Header 4', style: 'font-size: 12px; font-weight: bold;'},																
			h5: 		{exec: 'formatblock', name: '<h5>', title: 'Header 5', style: 'font-size: 10px; font-weight: bold;'}																	
		}
	},
	format: 
	{
		name: 'format', title: RLANG.format, func: 'show',
		dropdown: 
		{
			bold: 		  {exec: 'bold', name: 'Bold', title: RLANG.bold, style: 'font-weight: bold;'},
			italic: 	  {exec: 'italic', name: 'Italic', title: RLANG.italic, style: 'font-style: italic;'},
			Underline: 	{exec: 'Underline', name: 'Underline', title: 'Underline', style: 'text-decoration:underline!important;'},
			removeformat: {exec: 'RemoveFormat', name: 'RemoveFormat', title: 'Clear'}
		}						
	},
	lists: 	
	{
		name: 'lists', title: RLANG.lists, func: 'show',
		dropdown: 
		{
			ul: 	 {exec: 'insertunorderedlist', name: 'insertunorderedlist', title: '&bull; ' + RLANG.unorderedlist},
			ol: 	 {exec: 'insertorderedlist', name: 'insertorderedlist', title: '1. ' + RLANG.orderedlist},
			outdent: {exec: 'outdent', name: 'outdent', title: '< ' + RLANG.outdent},
			indent:  {exec: 'indent', name: 'indent', title: '> ' + RLANG.indent}
		}			
	},				
	image: { name: 'image', title: RLANG.image, func: 'showImage' },
	table:
	{ 
		name: 'table', title: RLANG.table, func: 'show',
		dropdown: 
		{
			insert_table: { name: 'insert_table', title: RLANG.insert_table, func: 'showTable' },
			separator_drop1: { name: 'separator' },	
			insert_row_above: { name: 'insert_row_above', title: RLANG.insert_row_above, func: 'insertRowAbove' },
			insert_row_below: { name: 'insert_row_below', title: RLANG.insert_row_below, func: 'insertRowBelow' },
			insert_column_left: { name: 'insert_column_left', title: RLANG.insert_column_left, func: 'insertColumnLeft' },
			insert_column_right: { name: 'insert_column_right', title: RLANG.insert_column_right, func: 'insertColumnRight' },												
			separator_drop2: { name: 'separator' },	
			add_head: { name: 'add_head', title: RLANG.add_head, func: 'addHead' },									
			delete_head: { name: 'delete_head', title: RLANG.delete_head, func: 'deleteHead' },							
			separator_drop3: { name: 'separator' },				
			delete_column: { name: 'insert_table', title: RLANG.delete_column, func: 'deleteColumn' },									
			delete_row: { name: 'delete_row', title: RLANG.delete_row, func: 'deleteRow' },									
			delete_table: { name: 'delete_table', title: RLANG.delete_table, func: 'deleteTable' }																		
		}		
	},
	link: 
	{
		name: 'link', title: RLANG.link, func: 'show',
		dropdown: 
		{
			link: 	{name: 'link', title: RLANG.link_insert, func: 'showLink'},
			unlink: {exec: 'unlink', name: 'unlink', title: RLANG.unlink}
		}			
	}
};