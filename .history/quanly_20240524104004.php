<?php
// Include database connection
include 'config.php';

// Số hàng hiển thị trên mỗi trang
$records_per_page = 5;

// Xác định trang hiện tại. Nếu không có trang nào được chỉ định, mặc định là trang 1
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Tính toán offset (vị trí bắt đầu của hàng trong truy vấn)
$offset = ($current_page - 1) * $records_per_page;

// Truy vấn dữ liệu từ cơ sở dữ liệu
$sql = "SELECT id, username, email, role FROM users LIMIT ?, ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "ii", $offset, $records_per_page);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Đếm tổng số hàng trong bảng
$total_rows_query = "SELECT COUNT(*) AS total FROM users";
$total_rows_result = mysqli_query($link, $total_rows_query);
$total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

// Tính toán số trang
$total_pages = ceil($total_rows / $records_per_page);

// Đóng kết nối
mysqli_stmt_close($stmt);
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Table</title>
<style>
// CSS TABLE
$table-cell-padding-y: 0.5rem;
$table-cell-padding-x: 0.5rem;
$table-cell-padding-y-sm: 0.25rem;
$table-cell-padding-x-sm: 0.25rem;

$table-cell-vertical-align: top;

$table-color: var(--#{$prefix}emphasis-color);
$table-bg: var(--#{$prefix}body-bg);
$table-accent-bg: transparent;

$table-th-font-weight: null;

$table-striped-color: $table-color;
$table-striped-bg-factor: 0.05;
$table-striped-bg: rgba(
  var(--#{$prefix}emphasis-color-rgb),
  $table-striped-bg-factor
);

$table-active-color: $table-color;
$table-active-bg-factor: 0.1;
$table-active-bg: rgba(
  var(--#{$prefix}emphasis-color-rgb),
  $table-active-bg-factor
);

$table-hover-color: $table-color;
$table-hover-bg-factor: 0.075;
$table-hover-bg: rgba(
  var(--#{$prefix}emphasis-color-rgb),
  $table-hover-bg-factor
);

$table-border-factor: 0.2;
$table-border-width: var(--#{$prefix}border-width);
$table-border-color: var(--#{$prefix}border-color);

$table-striped-order: odd;
$table-striped-columns-order: even;

$table-group-separator-color: currentcolor;

$table-caption-color: var(--#{$prefix}secondary-color);

$table-bg-scale: -80%;

/* Thêm CSS cho bảng và phân trang */
.table {
  width: 100%;
  border-collapse: collapse;
}
.table caption {
  font-size: 1.5em;
  margin: 0.5em 0 0.75em;
}
.table th,
.table td {
  padding: 0.625em;
  text-align: center;
}
.table th {
  background-color: #f2f2f2;
}
.pagination {
  display: flex;
  justify-content: center;
  margin: 1em 0;
}
.pagination a {
  margin: 0 0.5em;
  padding: 0.5em 1em;
  text-decoration: none;
  color: #007bff;
  border: 1px solid #dee2e6;
  border-radius: 0.25em;
}
.pagination a.active {
  background-color: #007bff;
  color: white;
  border-color: #007bff;
}

    .btn-block {
        padding: 10px;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    }

    .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            margin-top: 10px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
</style>
</head>
<body>

<h2>Quản lý người dùng</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
    </tr>
    <?php
    // Hiển thị dữ liệu từ kết quả truy vấn
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["role"] . "</td>";
        echo "</tr>";
    }
    ?>
</table>

<div class="pagination">
    <?php
    // Hiển thị các nút phân trang
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i'" . ($current_page == $i ? " class='active'" : "") . ">$i</a>";
    }
    ?>
</div>

<a href="index.php" class="btn btn-secondary btn-block">Quay về trang chủ</a>

</body>
</html>
