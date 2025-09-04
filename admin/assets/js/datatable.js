; (function () {
      if (typeof window.jQuery === 'undefined') return;
      var $ = window.jQuery;
      function initDatatable(selector, options) {
            var $tables = $(selector || 'table.table-dt');
            if (!$tables.length || typeof $.fn.DataTable !== 'function') return $tables;
            return $tables.each(function () {
                  var $t = $(this);
                  if ($t.data('dt-initialized')) return;
                  var defaultOpts = {
                        pageLength: 10,
                        lengthMenu: [5, 10, 25, 50, 100],
                        order: [],
                        autoWidth: false,
                        responsive: true
                  };
                  var merged = $.extend(true, {}, defaultOpts, options || {});
                  $t.DataTable(merged);
                  $t.data('dt-initialized', true);
            });
      }
      window.AdminInitDatatable = initDatatable;
      $(function () {
            initDatatable();
      });
})();


