<?php
declare(strict_types=1);

/**
 * Structure Menu Helper
 * 
 * Helps to build structured menu with levels
 * 
 * @package SpbShield
 * @since 1.0.0
 */

namespace SpbShield\Inc;

class StructureMenu {
    
    /**
     * Menu name or ID
     */
    private readonly string|int $menu_name;
    
    /**
     * Unsorted menu items
     * 
     * @var array<int, object>
     */
    private array $menu_items = [];
    
    /**
     * Sorted menu items
     * 
     * @var array<int, object>
     */
    private array $menu_struct_items = [];
    
    /**
     * Constructor
     * 
     * @param string|int $menu_name Menu name or ID
     */
    public function __construct(string|int $menu_name) {
        $this->menu_name = $menu_name;
    }
    
    /**
     * Get structured menu items
     * 
     * @return array<int, object> Structured menu items
     */
    public function getItemsStructureMenu(): array {
        if (empty($this->menu_name)) {
            return [];
        }
        
        // Get menu items
        $menu_items = wp_get_nav_menu_items($this->menu_name);
        
        if (!is_array($menu_items) || empty($menu_items)) {
            return [];
        }
        
        $this->menu_items = $menu_items;
        $this->menu_struct_items = $this->sort_structure_menu();
        
        return $this->menu_struct_items;
    }
    
    /**
     * Sort menu structure by levels
     * 
     * @return array<int, object> Sorted menu structure
     */
    private function sort_structure_menu(): array {
        if (empty($this->menu_items)) {
            return [];
        }
        
        $result = [];
        $current_level = 1;
        $last_level_item_menu = [];
        
        foreach ($this->menu_items as &$menu_item) {
            // Determine level based on parent
            $parent_id = (int) $menu_item->menu_item_parent;
            
            if ($parent_id === 0) {
                $current_level = 1;
            } else {
                $current_level = $this->find_menu_item_level($last_level_item_menu, $parent_id, $current_level);
            }
            
            // Store reference to current level item
            $last_level_item_menu[$current_level] = &$menu_item;
            
            // Add level property
            $menu_item->level_menu = $current_level;
            
            if ($current_level === 1) {
                // First level - add to result
                $result[] = $menu_item;
            } else {
                // Child level - add to parent's sub_item_menu
                $this->add_submenu_item($last_level_item_menu, $current_level);
            }
        }
        
        return $result;
    }
    
    /**
     * Find menu item level by parent
     * 
     * @param array<int, object> $last_level_item_menu Last level items
     * @param int $parent_id Parent menu item ID
     * @param int $current_level Current level
     * @return int Menu item level
     */
    private function find_menu_item_level(array $last_level_item_menu, int $parent_id, int $current_level): int {
        if (isset($last_level_item_menu[$current_level]) && 
            (int) $last_level_item_menu[$current_level]->ID === $parent_id) {
            // Previous item was our parent - increase level
            return $current_level + 1;
        }
        
        if (isset($last_level_item_menu[$current_level - 1]) && 
            (int) $last_level_item_menu[$current_level - 1]->ID === $parent_id) {
            // Same parent as previous - same level
            return $current_level;
        }
        
        // Level changed - find parent in all levels
        foreach ($last_level_item_menu as $level => $item_menu) {
            if ((int) $item_menu->ID === $parent_id) {
                return $level + 1;
            }
        }
        
        return $current_level;
    }
    
    /**
     * Add submenu item to parent
     * 
     * @param array<int, object> $last_level_item_menu Last level items
     * @param int $current_level Current level
     */
    private function add_submenu_item(array &$last_level_item_menu, int $current_level): void {
        $parent_level = $current_level - 1;
        
        if (!isset($last_level_item_menu[$parent_level])) {
            return;
        }
        
        if (!isset($last_level_item_menu[$parent_level]->sub_item_menu)) {
            $last_level_item_menu[$parent_level]->sub_item_menu = [];
        }
        
        $last_level_item_menu[$parent_level]->sub_item_menu[] = $last_level_item_menu[$current_level];
    }
}
