<?php
ob_start();
include 'inc/header.php';
include '../classes/user.php';
include '../classes/benh.php';
include '../classes/khoa.php';

if (Session::get('role') === '1') {
    $users = new Users();
    $benh = new Benh();
    $khoa = new Khoa();

    $getAllDSKhoa = $khoa->getAllKhoa();

    $message = null;
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        // Xử lý xóa tin tức và thông báo sau khi xóa thành công
        // $message = $tin_tuc->delete_tituc($id);
        // if ($message['status'] == 'success') {
        //     $_SESSION['message'] = $message;
        //     header("Location: " . $_SERVER['PHP_SELF']);
        //     exit();
        // }
    }
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
    }

    $tenBenh = isset($_GET['ten-benh']) ? $_GET['ten-benh'] : '';
    $IdKhoa = isset($_GET['id-khoa']) ? $_GET['id-khoa'] : '';

    $limit = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $list_benh = $benh->getPaginationBenhs($limit, $offset, $tenBenh, $IdKhoa);
    $total_articles = $benh->getTotalCountBenhs($tenBenh, $IdKhoa);
    $total_pages = ceil($total_articles / $limit);
?>

<style>
.action .action_edit {
    text-decoration: none;
    color: orange;
}

.action .action_delete {
    text-decoration: none;
    color: red;
}

.action .action_view {
    text-decoration: none;
    color: #01969a;
}
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Bệnh lý</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách bệnh lý</li>
    </ol>
</nav>

<form action="" method="get">
    <div class="row">
        <div class="col-sm-2">
            <div class="input-group mb-3">
                <input value="<?php echo $tenBenh; ?>" name="ten-benh" type="text" class="form-control"
                    placeholder="Nhập tên bệnh lý">
            </div>
        </div>
        <div class="col-sm-2">
            <select style="text-align: center;" name="id-khoa" id="id-khoa" class="form-select"
                aria-label="Default select example">
                <option value="">--- Chọn khoa ---</option>
                <?php
                    if ($getAllDSKhoa) {
                        while ($result = $getAllDSKhoa->fetch_assoc()) {
                            $selected = '';
                            if ($IdKhoa == $result['id']) {
                                $selected = 'selected';
                            }
                    ?>
                <option <?php echo $selected; ?> value="<?php echo $result['id']; ?>"><?php echo $result['name']; ?>
                </option>
                <?php
                        }
                    }
                    ?>
            </select>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-success">Tìm kiếm</button>
            <button style="margin-left: 10px;" class="btn btn-warning ">
                <a style="text-decoration: none; color: white;  "
                    href="<?php echo $local ?>/admin/benh-list.php">Clear</a>
            </button>
        </div>
    </div>
</form>

<div style="padding: 10px;">
    <table style="background-color: #a9c2c3; border-collapse: inherit; border-radius: 10px; "
        class="table table-striped table-hover">
        <thead>
            <tr>
                <th style="border-top-left-radius: 8px; " scope="col">ID</th>
                <th scope="col">Tên bệnh lý</th>
                <th scope="col">Khoa</th>
                <th scope="col">Slug</th>
                <th scope="col">Tình trạng</th>
                <th scope="col">Người tạo</th>
                <th scope="col">Ngày tạo</th>
            </tr>
        </thead>
        <tbody style="border-bottom-right-radius: 8px; ">
            <?php
                if ($list_benh) {
                    while ($result = $list_benh->fetch_assoc()) {
                        $hiden = 'Đang ẩn';
                        if ($result['hidden'] === '0') {
                            $hiden = 'Đang hiển thị';
                        }
                ?>
            <tr>
                <th scope="row"><?php echo $result['benh_id']; ?></th>
                <td><?php echo $result['name']; ?></td>
                <td><?php echo $result['nameKhoa']; ?></td>
                <td><?php echo $result['slug']; ?></td>
                <td style="width: 200px;">
                    <select style="text-transform: capitalize;" name="id_khoa" class="form-select"
                        onchange="getOptionValue(this, '<?php echo $result['ma_benh']; ?>')">
                        <?php if ($result['hidden'] === '0') { ?>
                        <option style="text-transform: capitalize;" value="0" selected>Đang hiển thị</option>
                        <option style="text-transform: capitalize;" value="1">Đang ẩn</option>
                        <?php } else { ?>
                        <option style="text-transform: capitalize;" value="0">Đang hiển thị</option>
                        <option style="text-transform: capitalize;" value="1" selected>Đang ẩn</option>
                        <?php } ?>
                    </select>
                </td>
                <td><?php echo $result['full_name']; ?></td>
                <td><?php echo $result['created_at']; ?></td>
            </tr>
            <?php
                    }
                }
                ?>
        </tbody>
    </table>
    <div style="display: flex; align-items: flex-end; justify-content: flex-end; ">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php if ($total_pages > 1) : ?>
                <?php if ($page > 1) : ?>
                <li class="page-item"><a class="page-link"
                        href="?ten-benh=<?php echo $tenBenh ?>&id-khoa=<?php echo $IdKhoa ?>&page=<?php echo $page - 1; ?>">Previous</a>
                </li>
                <?php endif; ?>

                <?php if ($page > 2) : ?>
                <li class="page-item"><a class="page-link"
                        href="?ten-benh=<?php echo $tenBenh ?>&id-khoa=<?php echo $IdKhoa ?>&page=1">1</a></li>
                <?php endif; ?>

                <?php if ($page > 3) : ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>

                <?php for ($i = max(1, $page - 1); $i <= min($page + 1, $total_pages); $i++) : ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>"><a class="page-link"
                        href="?ten-benh=<?php echo $tenBenh ?>&id-khoa=<?php echo $IdKhoa ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages - 2) : ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>

                <?php if ($page < $total_pages - 1) : ?>
                <li class="page-item"><a class="page-link"
                        href="?ten-benh=<?php echo $tenBenh ?>&id-khoa=<?php echo $IdKhoa ?>&page=<?php echo $total_pages; ?>"><?php echo $total_pages; ?></a>
                </li>
                <?php endif; ?>

                <?php if ($page < $total_pages) : ?>
                <li class="page-item"><a class="page-link"
                        href="?ten-benh=<?php echo $tenBenh ?>&id-khoa=<?php echo $IdKhoa ?>&page=<?php echo $page + 1; ?>">Next</a>
                </li>
                <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>

<script>
async function postData(url, data) {
    try {
        let response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        let result = await response.json();
        if (result.status === 'success') {
            toastr.success(result.message);
        } else {
            toastr.error(result.message);
        }
        return result;
    } catch (error) {
        toastr.error("Đã xảy ra lỗi khi gọi API: " + error.message);
        throw error;
    }
}

async function getOptionValue(select, ma_benh) {
    let hidden = select.value;
    let formData = {
        ma_benh: ma_benh,
        hidden: hidden
    };
    try {
        // Gọi đồng thời 5 API
        let responses = await Promise.all([
            postData("https://phukhoa.nhatvietclinic.vn/api/benh/update-hidden-benh.php", formData),

        ]);

        let errorFound = responses.some(response => response.status !== 'success');
        if (errorFound) {
            toastr.error("Có lỗi xảy ra khi thực hiện cập nhật.");
        } else {
            toastr.success("Cập nhật thành công.");
        }
    } catch (error) {
        toastr.error("Đã xảy ra lỗi khi gọi API: " + error.message);
    }
}

document.addEventListener("DOMContentLoaded", function() {
    <?php if ($message) : ?>
    toastr.<?php echo $message['status']; ?>('<?php echo $message['message']; ?>');
    <?php endif; ?>
});

function redirectToSelf() {
    location.href = "<?php echo $_SERVER['PHP_SELF']; ?>";
}
</script>

<?php
    include 'inc/footer.php';
} else {
?>
<div
    style="display: flex; align-items: center; justify-content: center; font-size: 30px; text-transform: uppercase; font-weight: 600; height: 90vh; color: red; ">
    Trang này không tồn tại</div>
<?php
}
?>