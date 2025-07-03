<?php
/**
 * Admin Menu.
 *
 * @since 2.0.0
 * @package WeddingSaas
 * @subpackage Admin
 */

namespace WDS\Admin;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Menu Class.
 */
class Menu {

	/**
	 * Get class pages.
	 */
	private function page() {
		return new Pages();
	}

	/**
	 * Get primary slug.
	 */
	private function slug() {
		return 'weddingsaas';
	}

	/**
	 * Seperator menu.
	 *
	 * @param string $context The seperator content.
	 */
	public function seperator_menu( $context = 'before' ) {
		if ( 'before' == $context ) {
			add_menu_page(
				'',
				'',
				'manage_options',
				'separator-wds-before',
				'',
				'',
				42.1822
			);
		} elseif ( 'after' == $context ) {
			add_menu_page(
				'',
				'',
				'manage_options',
				'separator-wds-after',
				'',
				'',
				44.8822
			);
		} elseif ( 'cpt' == $context ) {
			add_menu_page(
				'',
				'',
				'manage_options',
				'separator-wds-cpt',
				'',
				'',
				43.8822
			);
		}
	}

	/**
	 * Defines the welcome menu.
	 */
	public function define_welcome_menu() {
		$menus = array(
			'weddingsaas'                 => array(
				'parent'     => true,
				'group'      => 'welcome',
				'page_title' => __( 'Welcome to WeddingSaas', 'wds-notrans' ),
				'menu_title' => __( 'Welcome', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas', false ),
			),
			'weddingsaas-license'         => array(
				'group'      => 'welcome',
				'page_title' => __( 'WeddingSaas License', 'wds-notrans' ),
				'menu_title' => __( 'License', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas-license', false ),
				'callback'   => array( $this->page(), 'license_page' ),
			),
			'weddingsaas-install-plugins' => array(
				'group'      => 'welcome',
				'page_title' => __( 'Recomendation Plugins', 'wds-notrans' ),
				'menu_title' => __( 'Plugins', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas-install-plugins', false ),
			),
			'weddingsaas-system-info'     => array(
				'group'      => 'welcome',
				'page_title' => __( 'System Info', 'wds-notrans' ),
				'menu_title' => __( 'System', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas-system-info', false ),
				'callback'   => array( $this->page(), 'system_page' ),
			),
			'weddingsaas-shortcode'       => array(
				'group'      => 'welcome',
				'menu_title' => __( 'Shortcode', 'wds-notrans' ),
				'url'        => 'https://docs.pelatform.com/doc/1-shortcode-weddingsaas-qs8Ie2sQy5',
			),
		);

		if ( class_exists( 'WDS_Theme' ) ) {
			$menus['wds-theme-license'] = array(
				'group'      => 'welcome',
				'page_title' => __( 'WDS Theme License', 'wds-notrans' ),
				'menu_title' => __( 'Theme', 'wds-notrans' ),
				'url'        => menu_page_url( 'wds-theme-license', false ),
			);
		}

		return apply_filters( 'wds_admin_welcome_menu', $menus );
	}

	/**
	 * Defines the products menu.
	 */
	public function define_products_menu() {
		$menus = array(
			'weddingsaas-product'     => array(
				'parent'     => true,
				'group'      => 'products',
				'page_title' => __( 'Products', 'wds-notrans' ),
				'menu_title' => __( 'Products', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas-product', false ),
				'callback'   => array( $this->page(), 'product_page' ),
				'button'     => true,
				'btn_text'   => __( 'Add New', 'wds-notrans' ),
				'btn_url'    => menu_page_url( 'weddingsaas-product-new', false ),
			),
			'weddingsaas-product-new' => array(
				'group'      => 'products',
				'page_title' => __( 'Add New Product', 'wds-notrans' ),
				'menu_title' => __( 'Add New Product', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas-product-new', false ),
				'callback'   => array( $this->page(), 'product_new_page' ),
				'button'     => true,
				'btn_text'   => __( 'Back', 'wds-notrans' ),
				'btn_url'    => menu_page_url( 'weddingsaas-product', false ),
			),
			'weddingsaas-coupon'      => array(
				'group'      => 'products',
				'page_title' => __( 'Coupons', 'wds-notrans' ),
				'menu_title' => __( 'Coupons', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas-coupon', false ),
				'callback'   => array( $this->page(), 'coupon_page' ),
				'button'     => true,
				'btn_text'   => isset( $_GET['action'] ) && 'new' == $_GET['action'] ? __( 'Back', 'wds-notrans' ) : __( 'Add New', 'wds-notrans' ),
				'btn_url'    => isset( $_GET['action'] ) && 'new' == $_GET['action'] ? menu_page_url( 'weddingsaas-coupon', false ) : menu_page_url( 'weddingsaas-coupon', false ) . '&action=new',
			),
			'weddingsaas-addon'       => array(
				'group'      => 'products',
				'page_title' => __( 'Addons', 'wds-notrans' ),
				'menu_title' => __( 'Addons', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas-addon', false ),
			),
		);

		return apply_filters( 'wds_admin_products_menu', $menus );
	}

	/**
	 * Defines the invoices menu.
	 */
	public function define_invoices_menu() {
		$menus = array(
			'weddingsaas-invoice'   => array(
				'parent'     => true,
				'group'      => 'invoices',
				'page_title' => __( 'Invoices', 'wds-notrans' ),
				'menu_title' => __( 'Invoices', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas-invoice', false ),
				'callback'   => array( $this->page(), 'invoice_page' ),
			),
			'weddingsaas-order'     => array(
				'group'      => 'invoices',
				'page_title' => __( 'Orders', 'wds-notrans' ),
				'menu_title' => __( 'Orders', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas-order', false ),
				'callback'   => array( $this->page(), 'order_page' ),
			),
			'weddingsaas-statistic' => array(
				'group'      => 'invoices',
				'page_title' => __( 'Statistics', 'wds-notrans' ),
				'menu_title' => __( 'Statistics', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas-statistic', false ),
				'callback'   => array( $this->page(), 'statistic_page' ),
			),
		);

		return apply_filters( 'wds_admin_invoices_menu', $menus );
	}

	/**
	 * Defines the settings menu.
	 */
	public function define_settings_menu() {
		$menus = array(
			'weddingsaas-settings'  => array(
				'parent'     => true,
				'group'      => 'settings',
				'page_title' => __( 'WeddingSaas Settings', 'wds-notrans' ),
				'menu_title' => __( 'Settings', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas-settings', false ),
			),
			'weddingsaas-languages' => array(
				'group'      => 'settings',
				'page_title' => __( 'WeddingSaas Languages', 'wds-notrans' ),
				'menu_title' => __( 'Languages', 'wds-notrans' ),
				'url'        => menu_page_url( 'weddingsaas-languages', false ),
			),
		);

		return apply_filters( 'wds_admin_settings_menu', $menus );
	}

	/**
	 * Defines the replica menu.
	 */
	public function define_replica_menu() {
		$menus = array();
		if ( wds_is_replica() ) {
			$menus = array(
				'wds-replica-domain'    => array(
					'parent'     => true,
					'group'      => 'replica',
					'page_title' => __( 'Custome Domain List', 'wds-notrans' ),
					'menu_title' => __( 'Domain', 'wds-notrans' ),
					'url'        => menu_page_url( 'wds-replica-domain', false ),
					'callback'   => array( $this->page(), 'replica_domain_page' ),
				),
				'wds-replica-subdomain' => array(
					'group'      => 'replica',
					'page_title' => __( 'Subdomain List', 'wds-notrans' ),
					'menu_title' => __( 'Subdomain', 'wds-notrans' ),
					'url'        => menu_page_url( 'wds-replica-subdomain', false ),
					'callback'   => array( $this->page(), 'replica_subdomain_page' ),
				),
			);
		}

		return $menus;
	}

	/**
	 * Defines the cpt menu.
	 */
	public function define_cpt_menu() {
		$menus = array();

		// $menus = array(
		//  WDSS_MODEL . '-audio' => array(
		//      'group'      => 'cpt',
		//      'icon'       => WDS_ICON,
		//      'position'   => 44.1111,
		//      'page_title' => __( 'Audios', 'weddingsaas' ),
		//      'menu_title' => __( 'Audios', 'weddingsaas' ),
		//      'url'        => admin_url( 'edit.php?post_type=' . WDSS_MODEL . '-audio' ),
		//      'button'     => true,
		//      'btn_text'   => 'post-new.php' === $pagenow ? __( 'Back', 'weddingsaas' ) : __( 'Add New', 'weddingsaas' ),
		//      'btn_url'    => 'post-new.php' === $pagenow ? admin_url( 'edit.php?post_type=' . WDSS_MODEL . '-audio' ) : admin_url( 'post-new.php?post_type=' . WDSS_MODEL . '-audio' ),
		//  ),
		// );

		return apply_filters( 'wds_admin_cpt_menu', $menus );
	}

	/**
	 * Defines the all admin menu.
	 */
	public function define_all_menu() {
		$welcome_menu  = $this->define_welcome_menu();
		$products_menu = $this->define_products_menu();
		$invoices_menu = $this->define_invoices_menu();
		$settings_menu = $this->define_settings_menu();
		$replica_menu  = $this->define_replica_menu();
		$cpt_menu      = $this->define_cpt_menu();

		return array_merge( $welcome_menu, $products_menu, $invoices_menu, $settings_menu, $replica_menu, $cpt_menu );
	}

	/**
	 * Registes license menu.
	 */
	public function register_license_menu() {
		$this->seperator_menu( 'before' );
		$this->seperator_menu( 'after' );

		add_menu_page(
			'WeddingSaaS',
			'WeddingSaaS',
			'manage_options',
			'weddingsaas-license',
			array( $this->page(), 'license_page' ),
			WDS_ICON,
			42.2322
		);
	}

	/**
	 * Registes primary menu.
	 */
	public function register_primary_menu() {
		$this->seperator_menu( 'before' );
		$this->seperator_menu( 'after' );

		add_menu_page(
			'WeddingSaaS',
			'WeddingSaaS',
			'manage_options',
			$this->slug(),
			array( $this->page(), 'welcome_page' ),
			WDS_ICON,
			42.2322
		);

		$welcome_menus = $this->define_welcome_menu();
		foreach ( $welcome_menus as $slug => $menu ) {
			if ( isset( $menu['parent'] ) && $menu['parent'] ) {
				continue;
			}

			if ( isset( $menu['callback'] ) ) {
				add_submenu_page(
					$this->slug(),
					$menu['page_title'],
					$menu['menu_title'],
					'manage_options',
					$slug,
					$menu['callback']
				);
			}

			if ( isset( $menu['page_title'] ) ) {
				add_action(
					'admin_head',
					function () use ( $slug ) {
						remove_submenu_page( $this->slug(), $slug );
					}
				);
			}
		}

		$products_menus = $this->define_products_menu();
		foreach ( $products_menus as $slug => $menu ) {
			if ( isset( $menu['callback'] ) ) {
				add_submenu_page(
					$this->slug(),
					$menu['page_title'],
					$menu['menu_title'],
					'manage_options',
					$slug,
					$menu['callback']
				);
			} else {
				add_submenu_page(
					$this->slug(),
					$menu['page_title'],
					$menu['menu_title'],
					'manage_options',
					$slug,
				);
			}

			if ( ! isset( $menu['parent'] ) ) {
				add_action(
					'admin_head',
					function () use ( $slug ) {
						remove_submenu_page( $this->slug(), $slug );
					}
				);
			}
		}

		$invoices_menus = $this->define_invoices_menu();
		foreach ( $invoices_menus as $slug => $menu ) {
			if ( isset( $menu['callback'] ) ) {
				add_submenu_page(
					$this->slug(),
					$menu['page_title'],
					$menu['menu_title'],
					'manage_options',
					$slug,
					$menu['callback']
				);
			} else {
				add_submenu_page(
					$this->slug(),
					$menu['page_title'],
					$menu['menu_title'],
					'manage_options',
					$slug,
				);
			}

			if ( ! isset( $menu['parent'] ) ) {
				add_action(
					'admin_head',
					function () use ( $slug ) {
						remove_submenu_page( $this->slug(), $slug );
					}
				);
			}
		}

		$settings_menus = $this->define_settings_menu();
		foreach ( $settings_menus as $slug => $menu ) {
			if ( isset( $menu['callback'] ) ) {
				add_submenu_page(
					$this->slug(),
					$menu['page_title'],
					$menu['menu_title'],
					'manage_options',
					$slug,
					$menu['callback']
				);
			} else {
				add_submenu_page(
					$this->slug(),
					$menu['page_title'],
					$menu['menu_title'],
					'manage_options',
					$slug,
				);
			}

			if ( ! isset( $menu['parent'] ) ) {
				add_action(
					'admin_head',
					function () use ( $slug ) {
						remove_submenu_page( $this->slug(), $slug );
					}
				);
			}
		}

		$replica_menus = $this->define_replica_menu();
		if ( ! empty( $replica_menus ) ) {
			foreach ( $replica_menus as $slug => $menu ) {
				add_submenu_page(
					$this->slug(),
					$menu['page_title'],
					$menu['menu_title'],
					'manage_options',
					$slug,
					$menu['callback']
				);

				if ( ! isset( $menu['parent'] ) ) {
					add_action(
						'admin_head',
						function () use ( $slug ) {
							remove_submenu_page( $this->slug(), $slug );
						}
					);
				}
			}
		}

		$cpt_menus = $this->define_cpt_menu();
		if ( ! empty( $cpt_menus ) ) {
			$this->seperator_menu( 'cpt' );

			foreach ( $cpt_menus as $slug => $menu ) {
				$slug = 'edit.php?post_type=' . $slug;
				$sub  = wds_sanitize_data_field( $menu, 'submenu' );
				if ( ! $sub ) {
					add_menu_page(
						$menu['page_title'],
						$menu['menu_title'],
						'manage_options',
						$slug,
						'',
						$menu['icon'],
						$menu['position'],
					);
				} else {
					$parent = $menu['parent'];
					add_submenu_page(
						$parent,
						$menu['page_title'],
						$menu['menu_title'],
						'manage_options',
						$slug,
					);

					add_action(
						'admin_head',
						function () use ( $parent, $slug ) {
							remove_submenu_page( $parent, $parent );
							remove_submenu_page( $parent, $slug );
						}
					);
				}
			}
		}
	}

	/**
	 * Add the header to the pages.
	 */
	public function add_header_menu() {
		$page   = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : '';
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
		$data   = WDS()->session->get( 'admin_data' );
		if ( empty( $data['type'] ) || empty( $data['id'] ) || empty( $data['group'] ) ) {
			return;
		}

		$menus = array();

		if ( 'page' == $data['type'] ) {
			if ( 'welcome' == $data['group'] ) {
				$menus = $this->define_welcome_menu();
			} elseif ( 'products' == $data['group'] ) {
				$menus = $this->define_products_menu();
			} elseif ( 'invoices' == $data['group'] ) {
				$menus = $this->define_invoices_menu();
			} elseif ( 'settings' == $data['group'] ) {
				$menus = $this->define_settings_menu();
			} elseif ( 'replica' == $data['group'] ) {
				$menus = $this->define_replica_menu();
			}
		} elseif ( 'cpt' == $data['type'] ) {
			if ( 'cpt' == $data['group'] ) {
				$menus = $this->define_cpt_menu();
			}
		}

		if ( empty( $menus ) ) {
			return;
		}

		$page_title = isset( $menus[ $data['id'] ]['page_title'] ) ? $menus[ $data['id'] ]['page_title'] : '';
		$button     = isset( $menus[ $data['id'] ]['button'] ) ? $menus[ $data['id'] ]['button'] : false;

		include_once 'templates/menu_header.php';
		if ( 'cpt' != $data['group'] ) {
			$menu_cpt = false;
			include_once 'templates/menu_tab.php';
		} elseif ( 'cpt' == $data['group'] && wds_sanitize_data_field( $menus[ $data['id'] ], 'menu_tab' ) ) {
			$menu_cpt   = true;
			$menu_group = wds_sanitize_data_field( $menus[ $data['id'] ], 'menu_group' );
			include_once 'templates/menu_tab.php';
		}
	}

	/**
	 * Modify menu highlight for all pages.
	 */
	public function modify_menu_highlight() {
		global $submenu_file, $parent_file, $pagenow;

		if ( $this->page()->check() ) {
			$data  = WDS()->session->get( 'admin_data' );
			$type  = $data['type'];
			$id    = $data['id'];
			$group = $data['group'];

			if ( 'page' == $type ) {
				if ( 'welcome' == $group ) {
					$parent_file  = $this->slug();
					$submenu_file = $this->slug();
				} elseif ( 'products' == $group ) {
					$parent_file  = $this->slug();
					$submenu_file = 'weddingsaas-product';
				} elseif ( 'invoices' == $group ) {
					$parent_file  = $this->slug();
					$submenu_file = 'weddingsaas-invoice';
				} elseif ( 'settings' == $group ) {
					$parent_file  = $this->slug();
					$submenu_file = 'weddingsaas-settings';
				} elseif ( 'replica' == $group ) {
					$parent_file  = $this->slug();
					$submenu_file = 'wds-replica-domain';
				}
			} elseif ( 'cpt' == $type ) {
				$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
				if ( 'cpt' == $group && ( 'post.php' === $pagenow || 'edit.php' === $pagenow || 'post-new.php' === $pagenow ) ) {
					$cpt  = $this->define_cpt_menu();
					$sub  = wds_sanitize_data_field( $cpt[ $data['id'] ], 'submenu' );
					$prnt = wds_sanitize_data_field( $cpt[ $data['id'] ], 'parent' );
					foreach ( $cpt as $slug => $data ) {
						$files = $sub && $prnt ? $prnt : 'edit.php?post_type=' . $slug;
						if ( $screen->post_type == $slug ) {
							$parent_file  = $files;
							$submenu_file = $files;
						}
					}
				}
			}
		}
	}
}
