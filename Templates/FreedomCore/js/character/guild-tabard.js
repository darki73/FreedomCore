
/**
 * Renders a customized guild tabard using the HTML5 <canvas> element.
 *
 * @copyright   2010, Blizzard Entertainment, Inc
 * @class       GuildTabard
 * @example
 *
 *      var tabard = new GuildTabard('canvas-element', {
 *	 		'ring': 'alliance',
 *			'bg': [ 0, 2 ],
 *			'border': [ 0, 5 ],
 *			'emblem': [ 65, 12 ]
 *		});
 *
 */

function GuildTabard(id, tabard) {
	var self = this,
		canvas = document.getElementById(id),
		context = null,
		_path = Core.staticUrl + '/images/guild/tabards/',
		_width = canvas.width,
		_height = canvas.height,
		_src = [],
		_img = [],
		_colorMap = [],
		_color = [],
		_position = [];

	self.initialize = function() {
		if (canvas === null || !document.createElement('canvas').getContext || !_isInteger(tabard.bg[0]) || !_isInteger(tabard.border[0]) || !_isInteger(tabard.emblem[0]))
			return false;

		_colorMap = [
			null,
			null,
			[[215,32,112],[171,0,76],[87,0,0],[225,105,26],[180,56,0],[133,11,0],[237,151,22],[205,110,0],[155,61,0],[239,207,20],[207,162,0],[158,113,0],[226,216,20],[183,177,0],[133,128,0],[206,209,24],[159,161,3],[112,115,0],[153,206,27],[108,154,3],[65,108,0],[30,210,96],[4,157,63],[0,110,11],[29,206,169],[4,152,122],[0,107,74],[33,177,214],[3,109,139],[0,81,111],[72,125,193],[38,85,145],[0,39,98],[188,75,195],[145,42,155],[108,8,128],[202,17,191],[173,0,162],[124,0,116],[219,30,160],[149,0,97],[121,0,68],[160,108,44],[108,66,15],[53,16,0],[15,26,31],[117,124,120],[136,145,139],[156,166,159],[211,211,198],[229,107,140]],
			null,
			[[97,42,44],[109,69,46],[119,101,36],[118,114,36],[108,118,36],[85,108,48],[76,109,48],[48,108,66],[48,105,107],[48,80,108],[55,60,100],[87,54,100],[100,55,76],[103,51,53],[153,159,149],[38,46,38],[155,94,28]],
			[[102,0,32],[103,35,0],[103,69,0],[103,86,0],[98,102,0],[80,102,0],[54,102,0],[0,102,30],[0,102,86],[0,72,102],[9,42,94],[86,9,94],[93,10,79],[84,54,10],[177,183,176],[16,20,22],[221,163,90]]
		];

		_position = [
			[ 0, 0, (_width*216/240), (_width*216/240) ],
			[ (_width*18/240), (_width*27/240), (_width*179/240), (_width*216/240) ],
			[ (_width*18/240), (_width*27/240), (_width*179/240), (_width*210/240) ],
			[ (_width*18/240), (_width*27/240), (_width*179/240), (_width*210/240) ],
			[ (_width*31/240), (_width*40/240), (_width*147/240), (_width*159/240) ],
			[ (_width*33/240), (_width*57/240), (_width*125/240), (_width*125/240) ],
			[ (_width*18/240), (_width*27/240), (_width*179/240), (_width*32/240) ]
		];

		// If the tabard values exist
		if (_colorMap[2][tabard.bg[1]] && _colorMap[4][tabard.border[1]] && _colorMap[5][tabard.emblem[1]]) {
			_src = [
				_path + 'ring-' + tabard.ring + '.png',
				_path + 'shadow_' + Core.zeroFill(tabard.bg[0], 2) + '.png',
				_path + 'bg_' + Core.zeroFill(tabard.bg[0], 2) + '.png',
				_path + 'overlay_' + Core.zeroFill(tabard.bg[0], 2) + '.png',
				_path + 'border_' + Core.zeroFill(tabard.border[0], 2) + '.png',
				_path + 'emblem_' + Core.zeroFill(tabard.emblem[0], 2) + '.png',
				_path + 'hooks.png'
			];

			_color = [
				null,
				null,
				[ _colorMap[2][tabard.bg[1]][0], _colorMap[2][tabard.bg[1]][1], _colorMap[2][tabard.bg[1]][2] ],
				null,
				[ _colorMap[4][tabard.border[1]][0], _colorMap[4][tabard.border[1]][1], _colorMap[4][tabard.border[1]][2] ],
				[ _colorMap[5][tabard.emblem[1]][0], _colorMap[5][tabard.emblem[1]][1], _colorMap[5][tabard.emblem[1]][2] ],
				null
			];

			_img = [ new Image(), new Image(), new Image(), new Image(), new Image(), new Image(), new Image() ];

		// Else fallback to default tabard
		} else {
			_src = [
				_path + 'ring-' + tabard.ring + '.png',
				_path + 'shadow_00.png',
				_path + 'bg_00.png',
				_path + 'overlay_00.png',
				_path + 'hooks.png'
			];

			_img = [ new Image(), new Image(), new Image(), new Image(), new Image() ];
		}

		$(canvas).css('opacity', 0);
		context = canvas.getContext('2d');

		_loadImage(0);

		return true;
	};

	function _loadImage(count) {
		if (count >= _src.length) {
			_render(0);
			return;
		}
		$.ajax({
			'url': _src[count],
			'beforeSend': function() {
				_loadImage(count + 1);
			}
		});
	}

	function _render(index) {
		var _oldCanvas = new Image(),
			_newCanvas = new Image();

		_img[index].src = _src[index];

		_img[index].onload = function() {
			_oldCanvas.src = canvas.toDataURL('image/png');
		};

		_oldCanvas.onload = function() {
			canvas.width = 1;
			canvas.width = _width;
			context.drawImage(_img[index], _position[index][0], _position[index][1], _position[index][2], _position[index][3]);

			if (typeof _color[index] !== 'undefined' && _color[index] !== null) {
				_colorize(_color[index][0], _color[index][1], _color[index][2]);
			}

			_newCanvas.src = canvas.toDataURL('image/png');
			context.drawImage(_oldCanvas, 0, 0, _width, _height);
		};

		_newCanvas.onload = function() {
			context.drawImage(_newCanvas, 0, 0, _width, _height);
			index++;

			if (index < _src.length) {
				_render(index);
			} else {
				$(canvas).animate({opacity: 1}, 400);
			}
		};
	}

	function _colorize(r, g, b) {
		var imageData = context.getImageData(0, 0, _width, _height),
			pixelData = imageData.data,
			i = pixelData.length,
			intensityScale = 19,
			blend = 1 / 3,
			added_r = r / intensityScale + r * blend,
			added_g = g / intensityScale + g * blend,
			added_b = b / intensityScale + b * blend,
			scale_r = r / 255 + blend,
			scale_g = g / 255 + blend,
			scale_b = b / 255 + blend;

		do {
			if (pixelData[i + 3] !== 0) {
				pixelData[i] = pixelData[i] * scale_r + added_r;
				pixelData[i + 1] = pixelData[i + 1] * scale_g + added_g;
				pixelData[i + 2] = pixelData[i + 2] * scale_b + added_b;
			}
		} while (i -= 4);
		context.putImageData(imageData, 0, 0);
	}

	function _isInteger(n) {
		if (!isNaN(parseFloat(n)) && isFinite(n)) {
			return n % 1 === 0;
		} else {
			return false;
		}
	}

	this.initialize();
}