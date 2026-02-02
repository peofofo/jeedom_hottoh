/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.

function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="id" style="display : none;">';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name"></td>';
    tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="user"></td>';
    tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="key"></td>';
    tr += '<td>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="type" value="action" style="display : none;">';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="subType" value="message" style="display : none;">';
        if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
}*/

//$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
/*
 * Fonction pour l'ajout de commande, appellÃ© automatiquement par plugin.template
 */
function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (init(_cmd.configuration.type) == 'etat') {
      var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
      var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="id"></span>';
      tr += '</td>';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="type"></span>';
      tr += '</td>';
      tr += '<td>';
       tr += '<span class="cmdAttr" data-l1key="value"></span>';
      if(_cmd.type=='info' && is_numeric(_cmd.id)) {
      	tr += '<input class="cmdAttr form-control input-sm" id="'+ _cmd.id +'value" style="width : 200px;" readonly="true" value="">';
        $('#'+_cmd.id +'value').val("loading");
    	jeedom.cmd.execute({
          id: _cmd.id,
          cache: 0,
          notify: false,
          success: function(result) {
              $('#'+_cmd.id +'value').val(result);
          }
      	});
	  }
      if (init(_cmd.subType) == "numeric") {
      	tr += '<span class="cmdAttr" data-l1key="unite"></span> ';
      }
      tr += '</td>';
      tr += '<td>';
      if (_cmd.subType == 'numeric' || _cmd.subType == "binary") {
        tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span><br/>';
      }
      tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Affichage}}</label></span>';
      tr += '</td>';
      tr += '<td>';
      if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i>{{Tester}}</a>';
      }
      tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
      tr += '</td>';
      tr += '</tr>';
      $('#table_etat tbody').append(tr);
      $('#table_etat tbody tr:last').setValues(_cmd, '.cmdAttr');

  	}
  
  	if (init(_cmd.configuration.type) == 'temperature') {
      var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
     var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="id"></span>';
      tr += '</td>';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="type"></span>';
      tr += '</td>';
      tr += '<td>';
       tr += '<span class="cmdAttr" data-l1key="value"></span>';
      if(_cmd.type=='info' && is_numeric(_cmd.id)) {
      	tr += '<input class="cmdAttr form-control input-sm" id="'+ _cmd.id +'value" style="width : 200px;" readonly="true" value="">';
        $('#'+_cmd.id +'value').val("loading");
    	jeedom.cmd.execute({
          id: _cmd.id,
          cache: 0,
          notify: false,
          success: function(result) {
              $('#'+_cmd.id +'value').val(result);
          }
      	});
	  }
      if (init(_cmd.subType) == "numeric") {
      	tr += '<span class="cmdAttr" data-l1key="unite"></span> ';
      }
      tr += '</td>';
      tr += '<td>';
      if (_cmd.subType == 'numeric' || _cmd.subType == "binary") {
        tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span><br/>';
      }
      tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Affichage}}</label></span>';
      tr += '</td>';
      tr += '<td>';
      if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i>{{Tester}}</a>';
      }
      tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
      tr += '</td>';
      tr += '</tr>';
      $('#table_temperature tbody').append(tr);
      $('#table_temperature tbody tr:last').setValues(_cmd, '.cmdAttr');

  	}
  	
  	if (init(_cmd.configuration.type) == 'puissance') {
      var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
      var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="id"></span>';
      tr += '</td>';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="type"></span>';
      tr += '</td>';
      tr += '<td>';
       tr += '<span class="cmdAttr" data-l1key="value"></span>';
      if(_cmd.type=='info' && is_numeric(_cmd.id)) {
      	tr += '<input class="cmdAttr form-control input-sm" id="'+ _cmd.id +'value" style="width : 200px;" readonly="true" value="">';
        $('#'+_cmd.id +'value').val("loading");
    	jeedom.cmd.execute({
          id: _cmd.id,
          cache: 0,
          notify: false,
          success: function(result) {
              $('#'+_cmd.id +'value').val(result);
          }
      	});
	  }
      if (init(_cmd.subType) == "numeric") {
      	tr += '<span class="cmdAttr" data-l1key="unite"></span> ';
      }
      tr += '</td>';
      tr += '<td>';
      if (_cmd.subType == 'numeric' || _cmd.subType == "binary") {
        tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span><br/>';
      }
      tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Affichage}}</label></span>';
      tr += '</td>';
      tr += '<td>';
      if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i>{{Tester}}</a>';
      }
      tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
      tr += '</td>';
      tr += '</tr>';
      $('#table_puissance tbody').append(tr);
      $('#table_puissance tbody tr:last').setValues(_cmd, '.cmdAttr');

  	}
  
  	if (init(_cmd.configuration.type) == 'ventilateur') {
      var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
      var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="id"></span>';
      tr += '</td>';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="type"></span>';
      tr += '</td>';
      tr += '<td>';
       tr += '<span class="cmdAttr" data-l1key="value"></span>';
      if(_cmd.type=='info' && is_numeric(_cmd.id)) {
      	tr += '<input class="cmdAttr form-control input-sm" id="'+ _cmd.id +'value" style="width : 200px;" readonly="true" value="">';
        $('#'+_cmd.id +'value').val("loading");
    	jeedom.cmd.execute({
          id: _cmd.id,
          cache: 0,
          notify: false,
          success: function(result) {
              $('#'+_cmd.id +'value').val(result);
          }
      	});
	  }
      if (init(_cmd.subType) == "numeric") {
      	tr += '<span class="cmdAttr" data-l1key="unite"></span> ';
      }
      tr += '</td>';
      tr += '<td>';
      if (_cmd.subType == 'numeric' || _cmd.subType == "binary") {
        tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span><br/>';
      }
      tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Affichage}}</label></span>';
      tr += '</td>';
      tr += '<td>';
      if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i>{{Tester}}</a>';
      }
      tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
      tr += '</td>';
      tr += '</tr>';
      $('#table_ventilateur tbody').append(tr);
      $('#table_ventilateur tbody tr:last').setValues(_cmd, '.cmdAttr');

  	}
  
  	if (init(_cmd.configuration.type) == 'eau') {
      var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
     var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="id"></span>';
      tr += '</td>';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="type"></span>';
      tr += '</td>';
      tr += '<td>';
       tr += '<span class="cmdAttr" data-l1key="value"></span>';
      if(_cmd.type=='info' && is_numeric(_cmd.id)) {
      	tr += '<input class="cmdAttr form-control input-sm" id="'+ _cmd.id +'value" style="width : 200px;" readonly="true" value="">';
        $('#'+_cmd.id +'value').val("loading");
    	jeedom.cmd.execute({
          id: _cmd.id,
          cache: 0,
          notify: false,
          success: function(result) {
              $('#'+_cmd.id +'value').val(result);
          }
      	});
	  }
      if (init(_cmd.subType) == "numeric") {
      	tr += '<span class="cmdAttr" data-l1key="unite"></span> ';
      }
      tr += '</td>';
      tr += '<td>';
      if (_cmd.subType == 'numeric' || _cmd.subType == "binary") {
        tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span><br/>';
      }
      tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Affichage}}</label></span>';
      tr += '</td>';
      tr += '<td>';
      if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i>{{Tester}}</a>';
      }
      tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
      tr += '</td>';
      tr += '</tr>';
      $('#table_eau tbody').append(tr);
      $('#table_eau tbody tr:last').setValues(_cmd, '.cmdAttr');

  	}
  
  	if (init(_cmd.configuration.type) == 'timer') {
      var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
      var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="id"></span>';
      tr += '</td>';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="type"></span>';
      tr += '</td>';
      tr += '<td>';
       tr += '<span class="cmdAttr" data-l1key="value"></span>';
      if(_cmd.type=='info' && is_numeric(_cmd.id)) {
      	tr += '<input class="cmdAttr form-control input-sm" id="'+ _cmd.id +'value" style="width : 200px;" readonly="true" value="">';
        $('#'+_cmd.id +'value').val("loading");
    	jeedom.cmd.execute({
          id: _cmd.id,
          cache: 0,
          notify: false,
          success: function(result) {
              $('#'+_cmd.id +'value').val(result);
          }
      	});
	  }
      if (init(_cmd.subType) == "numeric") {
      	tr += '<span class="cmdAttr" data-l1key="unite"></span> ';
      }
      tr += '</td>';
      tr += '<td>';
      if (_cmd.subType == 'numeric' || _cmd.subType == "binary") {
        tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span><br/>';
      }
      tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Affichage}}</label></span>';
      tr += '</td>';
      tr += '<td>';
      if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i>{{Tester}}</a>';
      }
      tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
      tr += '</td>';
      tr += '</tr>';
      $('#table_timer tbody').append(tr);
      $('#table_timer tbody tr:last').setValues(_cmd, '.cmdAttr');
  	}
}