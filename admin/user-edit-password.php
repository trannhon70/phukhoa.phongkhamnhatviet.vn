<?php
include 'inc/header.php';
include '../classes/role.php';

if (Session::get('role') === '1') {
    $hoTen = "";
    $hoTen = isset($_GET['name']) ? $_GET['name'] : '';


?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Tài khoản</a></li>
        <li class="breadcrumb-item active" aria-current="page">Cập nhật mật khẩu</li>
    </ol>
</nav>
<div id="form-create-user" style="padding: 0px 25%;" class="row g-3 needs-validation">
    <div
        style="display: flex; align-items:center; justify-content: center; text-transform: capitalize; font-size: 25px; font-weight: 600; color: seagreen; ">
        <?php echo $hoTen; ?></div>

    <label for="validationCustom02" class="form-label">Nhập mật khẩu mới</label>
    <div style="margin-top: 0px;" class="input-group ">
        <span class="input-group-text"><i class="fas fa-lock"></i></span>
        <input class="form-control" type="password" id="password" name="password" placeholder="Password" value="">
        <span class="input-group-text"><i class="far fa-eye-slash" id="togglePassword"></i></span>
    </div>

    <label for="validationCustom02" class="form-label">Nhập lại mật khẩu mới</label>
    <div style="margin-top: 0px;" class="input-group ">
        <span class="input-group-text"><i class="fas fa-lock"></i></span>
        <input class="form-control" type="password" id="confirm_password" name="confirm_password"
            placeholder="Confirm Password" value="">
        <span class="input-group-text"><i class="far fa-eye-slash" id="togglePasswordConfirm"></i></span>
    </div>

    <div class="col-12 mt-4">
        <button class="btn btn-primary" name="submit">Cập nhật mật khẩu</button>
        <a href="user-list.php" class="btn btn-warning">Thoát</a>
    </div>
</div>

<script>
document.querySelector('button[name="submit"]').addEventListener('click', async function(event) {
    event.preventDefault();
    const urlParams = new URLSearchParams(window.location.search);
    const url = urlParams.get('edit');
    let form = document.getElementById('form-create-user');
    let inputs = form.getElementsByTagName('input');
    let formData = {};

    for (let i = 0; i < inputs.length; i++) {
        let input = inputs[i];
        formData[input.name] = input.value;
    }
    formData['ma_user'] = url;
    if (formData.password !== '' && formData.confirm_password !== '') {
        if (formData.password === formData.confirm_password) {

            try {
                let response1 = await postData(
                    "https://phukhoa.nhatvietclinic.vn/api/user/update-password.php", formData);
                if (response1.status === 'success') {
                    toastr.success(response1.message);
                    clearInputs(inputs);


                } else {
                    toastr.error(response1.message);
                }
            } catch (error) {
                toastr.error("Đã xảy ra lỗi khi gọi API: " + error.message);
            }
        } else {
            toastr.error("Mật khẩu nhập lại không đúng!");
        }

    } else {
        toastr.error("Mật khẩu không khớp hoặc trống!");
    }
});

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

function clearInputs(inputs) {
    for (let i = 0; i < inputs.length; i++) {
        let input = inputs[i];
        input.value = '';
    }
}

const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#password");

togglePassword.addEventListener("click", function() {
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
    this.classList.toggle('fa-eye');
});

const togglePasswordConfirm = document.querySelector("#togglePasswordConfirm");
const confirmPassword = document.querySelector("#confirm_password");

togglePasswordConfirm.addEventListener("click", function() {
    const type = confirmPassword.getAttribute("type") === "password" ? "text" : "password";
    confirmPassword.setAttribute("type", type);
    this.classList.toggle('fa-eye');
});
</script>

<?php include 'inc/footer.php'; ?>

<?php } else { ?>
<div
    style="display: flex; align-items: center; justify-content: center; font-size: 30px; text-transform: uppercase; font-weight: 600; height: 90vh; color: red;">
    Trang này không tồn tại</div>
<?php } ?>