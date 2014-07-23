<?php
/** A Stock Index Block **/
if(!class_exists('EPG_Stock_Index_Block')) {
    class EPG_Stock_Index_Block extends AQ_Block {
        function __construct() {
            $block_options = array(
                'name' => 'Stocks',
                'size' => 'span4',
            );
            parent::__construct('EPG_Stock_Index_Block', $block_options);
        }

        function form($instance) {
            $defaults = array(
                'title' => '',
                'symbols' => ''
            );
            $instance = wp_parse_args($instance, $defaults);
            extract($instance);
            ?>
            <p class="description">
                <label for="<?php echo $this->get_field_id('title') ?>">
                    Title
                    <?php echo aq_field_input('title', $block_id, $title, $size = 'full') ?>
                </label>
            </p>
            <p class="description">
                <label for="<?php echo $this->get_field_id('symbols') ?>">
                    <?php _e('Stock Symbols','engine'); ?>
                    <?php echo aq_field_input('symbols', $block_id, $symbols, $size = 'full') ?>
                </label>
            </p>

        <?php
            // Remove former results
            delete_transient( 'epg_stock_block_results' );
        }
        function block($instance) {
            extract($instance);

			// Temporarily delete cached data while fiddling with plugin.
			//delete_transient( 'epg_stock_block_results' );


            $stockSymbols = explode(", ", $symbols);
			$stock_url = '';
			$i = 0;
            foreach ($stockSymbols as $stock) {
                if ($i == 0) {
                    $stock = htmlentities($stock, ENT_QUOTES);
					$stock_url .= '"'. $stock . '"';
                } else {
                    $stock = htmlentities($stock, ENT_QUOTES);
					$stock_url .= ',"'. $stock . '"';
                }
                $i++;
            }

            $yql = 'http://query.yahooapis.com/v1/public/yql?q=select%20symbol%2CLastTradePriceOnly%2CChange%2CName%20from%20yahoo.finance.quotes%20where%20symbol%20in%20(' .
				urlencode($stock_url) .
				')&format=json&diagnostics=false&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=';

            if ( false === ( $last_saved_time = get_transient( 'epg_stock_block_time_saved') ) ) {
                $last_saved_time = time();
                set_transient( 'epg_stock_block_time_saved', $last_saved_time, 15 * MINUTE_IN_SECONDS );
            }
            if ( false === ( $json = get_transient( 'epg_stock_block_results' ) ) ) {
                // It wasn't there, so regenerate the data and save the transient
                // set timestamp
                delete_transient( 'epg_stock_block_time_saved' );
                $last_saved_time = time();
                set_transient( 'epg_stock_block_time_saved', $last_saved_time, 15 * MINUTE_IN_SECONDS );
                // set json
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $yql);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);//Use 1.1

                $json = curl_exec( $ch );
                set_transient( 'epg_stock_block_results', $json, 15 * MINUTE_IN_SECONDS );
            }

			$data = json_decode($json, TRUE);
            $quoteArr = $data['query']['results']['quote'];
            function priceChangeColor($str) {
                if ($str[0] === "-") {
                    $str = substr($str, 1);
                    $str = floatval($str);
                    $str = round($str, 2);
                    return '<span class="priceDown">-' . $str . '</span>';
                } else {
                    // returns digit rounded to hundreds
                    $str = round(floatval(substr($str, 1)), 2);
                    return '<span class="priceUp">+' . $str . '</span>';
                }
            }
            $stockData[] = "<thead><tr>" .
                "<th>Brand</th>" .
                "<th>Symbol</th>" .
                "<th>Price</th>" .
                "<th>Change</th>" .
                "</tr></thead>";
            foreach ($quoteArr as $quote) {
                $brand  = '<td class="sibrand">' . $quote['Name'] . '</td>';
                $symbol = '<td class="siSymbol">' . $quote['symbol'] . '</td>';
                $price  = '<td class="siPrice">' . $quote['LastTradePriceOnly'] . '</td>';
                $change = '<td class="siChange">(' . priceChangeColor($quote['Change']) . ')</td>';
                $row = '<tr>' .
                    $brand .
                    $symbol .
                    $price .
                    $change .
                    '</tr>';
                $stockData[] = $row;
            }
            $footer = '<tfoot><tr><td colspan="4">Data was last updated ' . strftime("%l:%M %p, %m/%e/%Y", ($last_saved_time - 18000)) .'</td></tr></tfoot>';
            // html code
            if($title) echo '<h4 class="widget-title">'.strip_tags($title).'</h4>';
            if($symbols) echo '<table class="widget-epgStockIndex">' . implode("", $stockData) . $footer . '</table>';
        }
    }
}