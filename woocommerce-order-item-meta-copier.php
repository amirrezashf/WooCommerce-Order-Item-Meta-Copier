<?php
/**
 * Plugin Name:       WooCommerce Order Item Meta Copier
 * Plugin URI:        https://github.com/amirrezashf/WooCommerce-Order-Item-Meta-Copier
 * Description:       Adds a copy button to WooCommerce order items for copying product variation and item meta values.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Requires Plugins:  woocommerce
 * Author:            Amirreza Shayesteh Far
 * Author URI:        https://amirrezaa.ir/
 * License:           GPL-3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       woocommerce-order-item-meta-copier
 *
 * @package WooCommerceOrderItemMetaCopier
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Order_Item_Meta_Copier' ) ) {

	/**
	 * Main plugin class.
	 */
	final class WC_Order_Item_Meta_Copier {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		private const VERSION = '1.0.0';

		/**
		 * Script handle.
		 *
		 * @var string
		 */
		private const SCRIPT_HANDLE = 'wc-order-item-meta-copier';

		/**
		 * Style handle.
		 *
		 * @var string
		 */
		private const STYLE_HANDLE = 'wc-order-item-meta-copier';

		/**
		 * Singleton instance.
		 *
		 * @var self|null
		 */
		private static $instance = null;

		/**
		 * Get plugin instance.
		 *
		 * @return self
		 */
		public static function instance(): self {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		private function __construct() {
			add_action( 'before_woocommerce_init', array( $this, 'declare_hpos_compatibility' ) );
			add_action( 'plugins_loaded', array( $this, 'init' ), 20 );
		}

		/**
		 * Declare WooCommerce HPOS compatibility.
		 *
		 * @return void
		 */
		public function declare_hpos_compatibility(): void {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
					'custom_order_tables',
					__FILE__,
					true
				);
			}
		}

		/**
		 * Initialize plugin.
		 *
		 * @return void
		 */
		public function init(): void {
			if ( ! class_exists( 'WooCommerce' ) ) {
				add_action( 'admin_notices', array( $this, 'render_missing_woocommerce_notice' ) );
				return;
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		}

		/**
		 * Display WooCommerce dependency notice.
		 *
		 * @return void
		 */
		public function render_missing_woocommerce_notice(): void {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}
			?>
			<div class="notice notice-warning">
				<p>
					<?php
					echo esc_html__(
						'WooCommerce Order Item Meta Copier requires WooCommerce to be installed and active.',
						'woocommerce-order-item-meta-copier'
					);
					?>
				</p>
			</div>
			<?php
		}

		/**
		 * Enqueue assets only on WooCommerce order edit screens.
		 *
		 * @return void
		 */
		public function enqueue_admin_assets(): void {
			if ( ! $this->is_order_edit_screen() ) {
				return;
			}

			wp_register_style(
				self::STYLE_HANDLE,
				false,
				array(),
				self::VERSION
			);

			wp_enqueue_style( self::STYLE_HANDLE );
			wp_add_inline_style( self::STYLE_HANDLE, $this->get_admin_css() );

			wp_register_script(
				self::SCRIPT_HANDLE,
				false,
				array(),
				self::VERSION,
				true
			);

			wp_enqueue_script( self::SCRIPT_HANDLE );
			wp_add_inline_script(
				self::SCRIPT_HANDLE,
				$this->get_admin_javascript()
			);
		}

		/**
		 * Check whether current page is a WooCommerce order edit screen.
		 *
		 * @return bool
		 */
		private function is_order_edit_screen(): bool {
			if ( ! is_admin() || ! function_exists( 'get_current_screen' ) ) {
				return false;
			}

			$screen = get_current_screen();

			if ( ! $screen ) {
				return false;
			}

			$screen_id = isset( $screen->id ) ? (string) $screen->id : '';
			$post_type = isset( $screen->post_type ) ? (string) $screen->post_type : '';

			$is_legacy_order_screen = 'shop_order' === $post_type;
			$is_hpos_order_screen   = false !== strpos( $screen_id, 'woocommerce_page_wc-orders' )
				|| false !== strpos( $screen_id, 'wc-orders' );

			return $is_legacy_order_screen || $is_hpos_order_screen;
		}

		/**
		 * Get admin JavaScript.
		 *
		 * @return string
		 */
		private function get_admin_javascript(): string {
			$settings = array(
				'buttonText'   => __( '📋 کپی مقادیر', 'woocommerce-order-item-meta-copier' ),
				'copiedText'   => __( '✓ کپی شد', 'woocommerce-order-item-meta-copier' ),
				'emptyMessage' => __( 'مقداری برای کپی کردن موجود نیست.', 'woocommerce-order-item-meta-copier' ),
				'errorMessage' => __( 'کپی در کلیپ‌بورد انجام نشد.', 'woocommerce-order-item-meta-copier' ),
			);

			return sprintf(
				'(function () {
					"use strict";

					var settings = %s;

					function normalizeText(value) {
						return String(value || "")
							.replace(/\u00a0/g, " ")
							.replace(/[ \t]+/g, " ")
							.replace(/\n{3,}/g, "\n\n")
							.trim();
					}

					function getTableText(table) {
						var lines = [];
						var rows = table.querySelectorAll("tr");

						rows.forEach(function (row) {
							var heading = row.querySelector("th");
							var value = row.querySelector("td");

							if (!heading || !value) {
								return;
							}

							var keyText = normalizeText(heading.textContent);
							var valueText = normalizeText(value.textContent);

							if (!keyText || !valueText) {
								return;
							}

							lines.push(keyText);
							lines.push(valueText);
							lines.push("");
						});

						while (lines.length && lines[lines.length - 1] === "") {
							lines.pop();
						}

						return lines.join("\n");
					}

					function fallbackCopy(text) {
						return new Promise(function (resolve, reject) {
							var textarea = document.createElement("textarea");

							textarea.value = text;
							textarea.setAttribute("readonly", "readonly");
							textarea.setAttribute("aria-hidden", "true");
							textarea.style.position = "fixed";
							textarea.style.opacity = "0";
							textarea.style.pointerEvents = "none";
							textarea.style.left = "-9999px";

							document.body.appendChild(textarea);
							textarea.select();
							textarea.setSelectionRange(0, textarea.value.length);

							try {
								var success = document.execCommand("copy");
								document.body.removeChild(textarea);

								if (success) {
									resolve();
								} else {
									reject(new Error("Copy command failed."));
								}
							} catch (error) {
								document.body.removeChild(textarea);
								reject(error);
							}
						});
					}

					function copyText(text) {
						if (
							navigator.clipboard
							&& typeof navigator.clipboard.writeText === "function"
							&& window.isSecureContext
						) {
							return navigator.clipboard.writeText(text);
						}

						return fallbackCopy(text);
					}

					function setButtonSuccess(button) {
						var originalText = button.dataset.originalText || settings.buttonText;

						button.textContent = settings.copiedText;
						button.classList.add("wc-oimc-success");

						window.setTimeout(function () {
							button.textContent = originalText;
							button.classList.remove("wc-oimc-success");
						}, 1800);
					}

					function createCopyButton(table) {
						if (!table || table.dataset.wcOimcReady === "1") {
							return;
						}

						table.dataset.wcOimcReady = "1";

						var button = document.createElement("button");

						button.type = "button";
						button.className = "button wc-oimc-copy-button";
						button.textContent = settings.buttonText;
						button.dataset.originalText = settings.buttonText;

						button.addEventListener("click", function () {
							var output = getTableText(table);

							if (!output) {
								window.alert(settings.emptyMessage);
								return;
							}

							button.disabled = true;

							copyText(output)
								.then(function () {
									setButtonSuccess(button);
								})
								.catch(function () {
									window.alert(settings.errorMessage);
								})
								.finally(function () {
									button.disabled = false;
								});
						});

						table.parentNode.insertBefore(button, table);
					}

					function initializeCopyButtons(root) {
						var context = root && root.querySelectorAll ? root : document;
						var tables = context.querySelectorAll("table.display_meta, .display_meta");

						tables.forEach(function (table) {
							if (table.tagName && table.tagName.toLowerCase() === "table") {
								createCopyButton(table);
							}
						});
					}

					function initializeObserver() {
						if (!window.MutationObserver || !document.body) {
							return;
						}

						var observer = new MutationObserver(function (mutations) {
							mutations.forEach(function (mutation) {
								mutation.addedNodes.forEach(function (node) {
									if (!node || node.nodeType !== 1) {
										return;
									}

									if (
										node.matches
										&& node.matches("table.display_meta")
									) {
										createCopyButton(node);
									}

									initializeCopyButtons(node);
								});
							});
						});

						observer.observe(document.body, {
							childList: true,
							subtree: true
						});
					}

					function initialize() {
						initializeCopyButtons(document);
						initializeObserver();
					}

					if (document.readyState === "loading") {
						document.addEventListener("DOMContentLoaded", initialize);
					} else {
						initialize();
					}
				}());',
				wp_json_encode( $settings )
			);
		}

		/**
		 * Get admin CSS.
		 *
		 * @return string
		 */
		private function get_admin_css(): string {
			return '
.wc-oimc-copy-button {
	align-items: center;
	display: inline-flex !important;
	gap: 4px;
	margin: 0 0 8px !important;
	min-height: 30px;
	transition:
		background-color 0.15s ease,
		border-color 0.15s ease,
		color 0.15s ease;
}

.wc-oimc-copy-button.wc-oimc-success {
	background: #edfaef !important;
	border-color: #46b450 !important;
	color: #157d00 !important;
}

.wc-oimc-copy-button:disabled {
	cursor: wait;
	opacity: 0.65;
}

table.display_meta + .wc-oimc-copy-button {
	margin-top: 8px !important;
}
';
		}
	}
}

WC_Order_Item_Meta_Copier::instance();
