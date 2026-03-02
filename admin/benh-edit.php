<?php
include 'inc/header.php';
include '../classes/role.php';
include '../classes/khoa.php';

if (Session::get('role') === '1') {
    $khoa = new khoa();
    $list_khoa = $khoa->getAllKhoa();

    if (!isset($_SESSION['ma_user'])) {
        $_SESSION['ma_user'] = 'ma_user';
    }

?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Bệnh lý</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo bệnh lý</li>
    </ol>
</nav>
<div id="form-create-user" style="padding: 0px 25%;" class="row g-3 needs-validation">
    <div class="col-12">
        <label for="validationCustom04" class="form-label">Khoa :</label>
        <select style="text-transform: capitalize;" name="id_khoa" class="form-select">
            <option selected disabled value="">chọn khoa</option>
            <?php if ($list_khoa) {
                    while ($result = $list_khoa->fetch_assoc()) {
                ?>
            <option style="text-transform: capitalize;" value="<?php echo $result['id']; ?>">
                <?php echo $result['name']; ?></option>
            <?php }
                } ?>
        </select>
    </div>
    <div class="col-12">
        <label for="validationCustom01" class="form-label">Tên bệnh lý</label>
        <input id="slugInput" name="name" type="text" class="form-control" value="">
    </div>
    <div class="col-12 mt-4">
        <button class="btn btn-primary" name="submit">Tạo bệnh lý</button>
        <a href="#" class="btn btn-warning">Thoát</a>
    </div>

</div>

<script>
let ma_user = <?php echo json_encode($_SESSION['ma_user']); ?>;

function generateRandomString(length) {
    let result = '';
    let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function getCurrentDateTimeString() {
    let now = new Date();
    let year = now.getFullYear();
    let month = String(now.getMonth() + 1).padStart(2, '0');
    let day = String(now.getDate()).padStart(2, '0');
    let hours = String(now.getHours()).padStart(2, '0');
    let minutes = String(now.getMinutes()).padStart(2, '0');
    let seconds = String(now.getSeconds()).padStart(2, '0');
    return `${year}${month}${day}${hours}${minutes}${seconds}`;
}

function removeVietnameseTones(str) {
    str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    str = str.replace(/đ/g, 'd').replace(/Đ/g, 'D');

    str = str.replace(/[^a-zA-Z0-9\s]/g, '');

    return str;
}

function generateSlug(title) {
    let slug = removeVietnameseTones(title.trim())
        .toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
    return slug;
}

document.querySelector('button[name="submit"]').addEventListener('click', async function(event) {
    event.preventDefault();
    //random mã
    let randomString = generateRandomString(8);
    let currentDateTimeString = getCurrentDateTimeString();
    let combinedString = randomString + currentDateTimeString;
    let ma_benh = combinedString.substring(0, 16);

    let form = document.getElementById('form-create-user');
    let inputs = form.getElementsByTagName('input');
    let select = form.getElementsByTagName('select')[0];
    let selectedText = select.options[select.selectedIndex].text
    let formData = {};

    for (let i = 0; i < inputs.length; i++) {
        let input = inputs[i];
        formData[input.name] = input.value;
    }

    if (select.value !== "" && formData.name !== '') {
        //get thông tin slug
        const slugInput = document.getElementById("slugInput");
        let slug = generateSlug(slugInput.value);
        let slugKhoa = generateSlug(selectedText);
        let link = `/${slugKhoa}/${slug}.html`;
        let session = `/${slugKhoa}/${slug}`;

        formData[select.name] = select.value;
        formData['ma_benh'] = ma_benh;
        formData['slug'] = slug;
        formData['ma_user'] = ma_user;
        formData['link'] = link;
        formData['session'] = session;

        try {
            // First API endpoint
            let response1 = await postData("https://phukhoa.nhatvietclinic.vn/api/benh/create-benh.php",
                formData);
            if (response1.status === 'success') {
                toastr.success(response1.message);
                // Clear inputs after success
                clearInputs(inputs);


            } else {
                toastr.error(response1.message);
            }
        } catch (error) {
            toastr.error("Đã xảy ra lỗi khi gọi API: " + error.message);
        }

    } else {
        toastr.error("Tất cả các trường không được bỏ trống!");
    }
});

// Function to send POST request and return promise
function postData(url, data) {
    return new Promise((resolve, reject) => {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    resolve(JSON.parse(xhr.responseText));
                } else {
                    reject(new Error("Request failed with status: " + xhr.status));
                }
            }
        };
        xhr.send(JSON.stringify(data));
    });
}

// Function to clear input values
function clearInputs(inputs) {
    for (let i = 0; i < inputs.length; i++) {
        let input = inputs[i];
        input.value = '';
    }
}
</script>

<?php include 'inc/footer.php'; ?>

<?php } else { ?>
<div
    style="display: flex; align-items: center; justify-content: center; font-size: 30px; text-transform: uppercase; font-weight: 600; height: 90vh; color: red; ">
    Trang này không tồn tại</div>
<?php } ?>