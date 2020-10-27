/**
 * ownCloud Wopi
 *
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 * @copyright 2018 ownCloud GmbH.
 *
 * This code is covered by the ownCloud Commercial License.
 *
 * You should have received a copy of the ownCloud Commercial License
 * along with this program. If not, see <https://owncloud.com/licenses/owncloud-commercial/>.
 *
 */
(function ($, OC, OCA) {

	OCA.Wopi = {

		initialize: function () {
			var data = $("data[key='wopi']");
			var fileId = data.attr('data-id');
			var mime = data.attr('data-mime');
			var ext = data.attr('data-ext').toLowerCase();
			var action = data.attr('data-action');
			var fileName = data.attr('data-fileName');
			if (!fileId || !mime || !ext || !action) {
				OC.Notification.showTemporary(t('wopi', 'Access denied'));
				return;
			}
			OCA.Wopi.loadDiscovery().then(function (config) {
				// Check action for given file mime and extension
				var actionUrl = config[action][mime][ext];
				if (typeof actionUrl !== 'undefined') {
					$("link[rel='icon']").attr("href", config.favicons[mime]);
					document.title = fileName + ' - ownCloud';
					OCA.Wopi.openDoc(fileId, actionUrl);
				}
			}, function (error) {
				console.error(error);
			});
		},

		openDoc: function (fileId, actionUrlTemplate) {
			$.post(OC.generateUrl('/apps/wopi/token'), {
				fileId: fileId,
				folderUrl: window.location.href
			}).success(function (response) {
				var actionUrlTemplateElements = actionUrlTemplate.split('<');
				var lang = OCA.WopiLang.getLocale();
				var wopisrc = window.location.protocol + '//' + window.location.host +
					OC.generateUrl('/apps/wopi/files/{fileId}', {fileId: fileId});

				// Construct action url with all required placeholders
				var actionUrl = actionUrlTemplateElements[0];
				actionUrl += 'ui=' + lang +  '&rs=' + lang;
				actionUrl += '&WOPISrc=' + encodeURIComponent(wopisrc);

				var view = '<form id="office_form" name="office_form" target="office_frame"\n' +
					'      action="ACTION_URL" method="post">\n' +
					'    <input name="access_token" value="ACCESS_TOKEN_VALUE" type="hidden"/>\n' +
					'    <input name="access_token_ttl" value="ACCESS_TOKEN_TTL_VALUE" type="hidden"/>\n' +
					'</form>';
				view = view.replace('ACTION_URL', actionUrl);
				view = view.replace('ACCESS_TOKEN_VALUE', response.token);
				view = view.replace('ACCESS_TOKEN_TTL_VALUE', response.expires);
				$('#content').append(view);

				var frameholder = document.getElementById('frameholder');
				var officeFrame = document.createElement('iframe');
				officeFrame.name = 'office_frame';
				officeFrame.id ='office_frame';

				// The title should be set for accessibility
				officeFrame.title = 'Office Online Frame';

				// This attribute allows true fullscreen mode in slideshow view
				// when using PowerPoint Online's 'view' action.
				officeFrame.setAttribute('allowfullscreen', 'true');
				officeFrame.setAttribute('style', 'width:100%;height:100%;display:block;position:absolute;top:0;z-index:200;');

				frameholder.appendChild(officeFrame);

				document.getElementById('office_form').submit();

				$("#office_close_button").click(function (e) {
					e.preventDefault();
					$('#office_container').remove();
				});

			});
		},

		loadDiscovery: function () {
			return new Promise(function (resolve, reject) {
				$.ajax({
					type: "get",
					url: OC.generateUrl('/apps/wopi/discovery.json'),
					success: function(data) {
						resolve(data);
					},
					error: function(xhr, status) {
						reject(Error(status));
					}
				});
			});
		},

	};

	$(document).ready(function () {
		OCA.Wopi.initialize();
	});

})(jQuery, OC, OCA);
