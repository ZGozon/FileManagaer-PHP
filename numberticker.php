    <!-- Number Ticker -->

    <link rel='stylesheet prefetch' href='https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css'>
    <style class="cp-pen-styles">
    .col_half { width: 49%; }
    .col_third { width: 32%; }
    .col_fourth { width: 23.5%; }
    .col_fifth { width: 18.4%; }
    .col_sixth { width: 15%; }
    .col_three_fourth { width: 74.5%;} 
    .col_twothird{ width: 66%;}
    .col_half,
    .col_third,
    .col_twothird,
    .col_fourth,
    .col_three_fourth,
    .col_fifth{
        position: relative;
        display:inline;
        display: inline-block;
        float: left;
        margin-right: 2%;
        margin-bottom: 20px;
    }
    .end { margin-right: 0 !important; }
    /* Column Grids End */

    .wrapper { width: 980px; margin: 30px auto; position: relative;}
    .counter { background-color: #faf9f9; padding: 20px 0; border-radius: 5px;}
    .count-title { font-size: 40px; font-weight: normal;  margin-top: 10px; margin-bottom: 0; text-align: center; }
    .count-text { font-size: 13px; font-weight: normal;  margin-top: 10px; margin-bottom: 0; text-align: center; }
    .fa-2x { margin: 0 auto; float: none; display: table; color: #4ad1e5; }
    </style></head><body>
<div>
    <div class="wrapper" style="margin-left: 480px;">
        <div class="counter col_fourth">
          <i class="fa fa-info-circle fa-2x"></i>
          <h4><center><?php echo fm_get_filesize($all_files_size) ?></center></h4>
           <p class="count-text "> Full size of files</p>
        </div>

        <div class="counter col_fourth">
          <i class="fa fa-file fa-2x"></i>
          <h2 class="timer count-title count-number" data-to='<?php echo $num_files ?>' data-speed="1500"></h2>
          <p class="count-text ">Files</p>
        </div>

        <div class="counter col_fourth">
          <i class="fa fa-folder fa-2x"></i>
          <h2 class="timer count-title count-number" data-to='<?php echo $num_folders ?>' data-speed="1500"></h2>
          <p class="count-text ">Folders</p>
        </div>
    </div>

    </div>
    <script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script>
    <script >(function ($) {
        $.fn.countTo = function (options) {
            options = options || {};
            
            return $(this).each(function () {
                // set options for current element
                var settings = $.extend({}, $.fn.countTo.defaults, {
                    from:            $(this).data('from'),
                    to:              $(this).data('to'),
                    speed:           $(this).data('speed'),
                    refreshInterval: $(this).data('refresh-interval'),
                    decimals:        $(this).data('decimals')
                }, options);
                
                // how many times to update the value, and how much to increment the value on each update
                var loops = Math.ceil(settings.speed / settings.refreshInterval),
                    increment = (settings.to - settings.from) / loops;
                
                // references & variables that will change with each update
                var self = this,
                    $self = $(this),
                    loopCount = 0,
                    value = settings.from,
                    data = $self.data('countTo') || {};
                
                $self.data('countTo', data);
                
                // if an existing interval can be found, clear it first
                if (data.interval) {
                    clearInterval(data.interval);
                }
                data.interval = setInterval(updateTimer, settings.refreshInterval);
                
                // initialize the element with the starting value
                render(value);
                
                function updateTimer() {
                    value += increment;
                    loopCount++;
                    
                    render(value);
                    
                    if (typeof(settings.onUpdate) == 'function') {
                        settings.onUpdate.call(self, value);
                    }
                    
                    if (loopCount >= loops) {
                        // remove the interval
                        $self.removeData('countTo');
                        clearInterval(data.interval);
                        value = settings.to;
                        
                        if (typeof(settings.onComplete) == 'function') {
                            settings.onComplete.call(self, value);
                        }
                    }
                }
                
                function render(value) {
                    var formattedValue = settings.formatter.call(self, value, settings);
                    $self.html(formattedValue);
                }
            });
        };
        
        $.fn.countTo.defaults = {
            from: 0,               // the number the element should start at
            to: 0,                 // the number the element should end at
            speed: 1000,           // how long it should take to count between the target numbers
            refreshInterval: 100,  // how often the element should be updated
            decimals: 0,           // the number of decimal places to show
            formatter: formatter,  // handler for formatting the value before rendering
            onUpdate: null,        // callback method for every time the element is updated
            onComplete: null       // callback method for when the element finishes updating
        };
        
        function formatter(value, settings) {
            return value.toFixed(settings.decimals);
        }
    }(jQuery));

    jQuery(function ($) {
      // custom formatting example
      $('.count-number').data('countToOptions', {
        formatter: function (value, options) {
          return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
        }
      });
      
      // start all the timers
      $('.timer').each(count);  
      
      function count(options) {
        var $this = $(this);
        options = $.extend({}, options || {}, $this.data('countToOptions') || {});
        $this.countTo(options);
      }
    });
    //# sourceURL=pen.js
    </script>';
