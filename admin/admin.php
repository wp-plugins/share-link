<div class="wrap">
    <h2>Plugin Name</h2>
    <nav>
        <ul>
            <li><a href="admin.php?page=plugin-admin.php?i=menu-item-1">Menu Item 1</a></li>
            <li><a href="admin.php?page=plugin-admin.php?i=menu-item-2">Menu Item 2</a></li>
            <li><a href="admin.php?page=plugin-admin.php?i=menu-item-3">Menu Item 3</a></li>
            <li><a href="admin.php?page=plugin-admin.php?i=menu-item-4">Menu Item 4</a></li>
        </ul>
        <div class="clear"></div>
    </nav>


    <div class="content">
        <?php
        $include = $_GET["i"];
        include("admin/" . $include . ".php");
        ?>
    </div>
</div>