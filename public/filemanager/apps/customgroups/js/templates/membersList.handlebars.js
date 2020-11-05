(function() {
  var template = Handlebars.template, templates = OCA.CustomGroups.Templates = OCA.CustomGroups.Templates || {};
templates['membersList'] = template({"compiler":[8,">= 4.3.0"],"main":function(container,depth0,helpers,partials,data) {
    return "<div class=\"header\">\n</div>\n<table class=\"grid hidden\">\n	<thead>\n		<th></th>\n		<th>Member</th>\n		<th>Role</th>\n		<th></th>\n	</thead>\n	<tbody class=\"group-member-list\">\n	</tbody>\n</table>\n<div class=\"loading loading-list\" style=\"height: 50px\"></div>\n";
},"useData":true});
})();
