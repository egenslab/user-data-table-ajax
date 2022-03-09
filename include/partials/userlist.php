<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Name
            <span id="orderByDesc">
                <i class="bi bi-arrow-up"></i>
             </span>
            <span id="orderByAsc">
                  <i class="bi bi-arrow-down"></i>
            </span>
        </th>
        <th scope="col">Username</th>
        <th scope="col">Email</th>
        <th scope="col">Role</th>
        <th scope="col">Created At</th>
    </tr>
    </thead>
    <tbody id="mainTableContent">
    <?php $i = $offset; foreach($query as $q) {
        $i++;
        $user_meta = get_userdata($q->ID);
        $user_roles = $user_meta->roles;
        ?>
        <tr>
            <th scope="row"><?php echo $i; ?></th>
            <td><?=$q->display_name?></td>
            <td><?=$q->user_login?></td>
            <td><?=$q->user_email?></td>
            <td><?php echo $user_roles[0]; ?></td>
            <td><?=$q->user_registered?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php
    $per_page=10;
    $no_of_paginations = ceil($totalUsers / $per_page);

    if ($cur_page >= 7) {
        $start_loop = $cur_page - 3;
        if ($no_of_paginations > $cur_page + 3)
            $end_loop = $cur_page + 3;
        else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
            $start_loop = $no_of_paginations - 6;
            $end_loop = $no_of_paginations;
        } else {
            $end_loop = $no_of_paginations;
        }
    } else {
        $start_loop = 1;
        if ($no_of_paginations > 7)
            $end_loop = 7;
        else
            $end_loop = $no_of_paginations;
    }

    $per_page = 2;
    $previous_btn = true;
    $next_btn = true;

    // Pagination Buttons
    $pag_container = "
        <div class='pagination-link'>
          <ul>";
    if ($previous_btn && $cur_page > 1) {
        $pre = $cur_page - 1;
        $pag_container .= "<li p='$pre' class='active'>Previous</li>";
    } else if ($previous_btn) {
        $pag_container .= "<li class='inactive'>Previous</li>";
    }
    for ($i = $start_loop; $i <= $end_loop; $i++) {
        if ($cur_page == $i)
            $pag_container .= "<li p='$i' class = 'selected' >{$i}</li>";
        else
            $pag_container .= "<li p='$i' class='active'>{$i}</li>";
    }
    if ($next_btn && $cur_page < $no_of_paginations) {
        $nex = $cur_page + 1;
        $pag_container .= "<li p='$nex' class='active'>Next</li>";
    } else if ($next_btn) {
        $pag_container .= "<li class='inactive'>Next</li>";
    }

    $pag_container = $pag_container . "
          </ul>
        </div>";

    echo '<div class = "pagination-nav">' . $pag_container . '</div>';
?>