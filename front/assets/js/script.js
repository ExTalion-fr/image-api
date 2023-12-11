
let user = {
    id: 0,
    username: '',
    images: []
}

let currentImageId = 0;
let currentImage = null;

let contentImageActive = false;

let fileReady = false;
let file = null;

$(function(){
    window.onload = (e) => {

        let loginButton = $('#login-button');
        let logoutButton = $('#logout-button');
        let deleteButton = $('#delete-button');
        let addButton = $('#add-button');
        let fileInput = $('#file-input');
        let fileButton = $('#file-button');

        fileButton.on('click', function(e) {
            e.preventDefault();
            if (file === null) {
                alert('Please select a file');
                return;
            }
            const formData = new FormData();
            formData.append('image', file);
            $.ajax({
                url: "https://api.imgur.com/3/image",
                type: "POST",
                datatype: "json",
                headers: {
                    "Authorization": "Client-ID 4513d61c40c0637"
                },
                data: formData,
                success: function(response) {
                    const PAT = 'a9b58d05e8d44cbb87406ee5aca4ae03';
                    const USER_ID = 'clarifai';       
                    const APP_ID = 'main';
                    // Change these to whatever model and image URL you want to use
                    const MODEL_ID = 'general-image-recognition';
                    const MODEL_VERSION_ID = 'aa7f35c01e0642fda5cf400f543e7c40';    
                    const IMAGE_URL = response.data.link;
                    const raw = JSON.stringify({
                        "user_app_id": {
                            "user_id": USER_ID,
                            "app_id": APP_ID
                        },
                        "inputs": [
                            {
                                "data": {
                                    "image": {
                                        "url": IMAGE_URL
                                    }
                                }
                            }
                        ]
                    });
                    const requestOptions = {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Key ' + PAT
                        },
                        body: raw
                    };
                    fetch("https://api.clarifai.com/v2/models/" + MODEL_ID + "/versions/" + MODEL_VERSION_ID + "/outputs", requestOptions)
                        .then(response => response.text())
                        .then(result => {
                            result = JSON.parse(result);
                            let tags = [];
                            let i = 1;
                            result.outputs[0].data.concepts.forEach(tag => {
                                if (i > 5) {
                                    return;
                                }
                                tags.push({
                                    id: tag.id,
                                    name: tag.name,
                                    value: tag.value
                                });
                                i++;
                            });
                            file = null;
                            console.log(response);
                            $.post('http://127.0.0.1/image-api/api-rest/image/add.php', {user_id: user.id, url: response.data.link, tags: JSON.stringify(tags)})
                            .done(function(data){
                                if (data.success === 1) {
                                    user.images.push(data.image);
                                    updateImages();
                                    updateTags();
                                    addButton.find('i').removeClass('fa-xmark');
                                    addButton.find('i').addClass('fa-plus');
                                    $('#content-add').removeClass('active');
                                    contentImageActive = false;
                                } else {
                                    alert(data.message);
                                }
                            });
                        })
                        .catch(error => console.log('error', error));
                    
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

        fileInput.on('change', function(e) {
            e.preventDefault();
            file = fileInput[0].files[0];
            $('#file-label span').html(file.name);
            console.log(file);
        });

        addButton.on('click', function(e) {
            e.preventDefault();
            if (!contentImageActive) {
                addButton.find('i').removeClass('fa-plus');
                addButton.find('i').addClass('fa-xmark');
                $('#content-add').addClass('active');
            } else {
                addButton.find('i').removeClass('fa-xmark');
                addButton.find('i').addClass('fa-plus');
                $('#content-add').removeClass('active');
            }
            contentImageActive = !contentImageActive;
        });

        deleteButton.on('click', function(e) {
            e.preventDefault();
            if (currentImageId !== 0) {
                $.get('http://127.0.0.1/image-api/api-rest/image/delete.php?id=' + currentImageId)
                .done(function(data){
                    if (data.success === 1) {
                        user.images = user.images.filter(image => image.id !== currentImageId);
                        currentImageId = 0;
                        currentImage = null;
                        $('#delete-button').addClass('disable');
                        updateImages();
                        updateTags();
                    } else {
                        alert(data.message);
                    }
                });
            }
        });

        logoutButton.on('click', function(e) {
            e.preventDefault();
            Cookies.remove('id');
            Cookies.remove('username');
            user.id = 0;
            user.username = '';
            $('#page-login').css('display', 'flex');
            $('#page-image').hide();
        });

        loginButton.on('click', function(e) {
            e.preventDefault();
            $username = $('#username').val();
            $password = $('#password').val();

            if ($username !== '' && $password !== '') {
                $.post('http://127.0.0.1/image-api/api-rest/login.php', {username: $username, password: $password})
                .done(function(data){
                    if (data.success === 1) {
                        Cookies.set('id', data.id);
                        Cookies.set('username', data.username);
                        login();
                    } else {
                        alert(data.message);
                    }
                });
            } else {
                alert('Please fill in all the fields');
            }
        });

        if (Cookies.get('id') && Cookies.get('username') && Cookies.get('id') !== '' && Cookies.get('username') !== '') {
            login();
        } else {
            $('#page-login').css('display', 'flex');
        }

    }
});

function login() {
    user.id = Cookies.get('id');
    user.username = Cookies.get('username');
    currentImageid = 0;
    currentImage = null;
    $('#delete-button').addClass('disable');
    $('#page-login').hide();
    $('#page-image').css('display', 'flex');
    $('header h1').html(user.username);
    $.get('http://127.0.0.1/image-api/api-rest/image/getImagesByUserId.php?id=' + user.id)
    .done(function(data){
        if (data.success === 1) {
            user.images = data.images;
            updateImages();
            updateTags();
        } else {
            alert(data.message);
        }
    });
}

function updateImages(images = null) {
    let imageList = $('#image-list');
    imageList.empty();
    tagsCategory = [];
    if (images === null) {
        images = user.images;
    }
    images.forEach(image => {
        let tags = JSON.parse(image.tags);
        tags.forEach(tag => {
            if (tagsCategory.find(tagCategory => tagCategory.id === tag.id)) {
                return;
            } else {
                tagsCategory.push(tag);
            }
        });
        let newDiv = `<div class="image-list-item" data-id="` + image.id + `">
            <img src="` + image.url + `">
            <div class="image-list-item-info">` +
                tags.map(tag => {
                    return `<span>` + tag.name + ` ` + round(tag.value * 100, 1) + `</span>`;
                }).join('')
            + `</div>
        </div>`;
        imageList.append(newDiv);
        $('.image-list-item:last-of-type').on('click', function(e) {
            e.preventDefault();
            currentImageId = $(this).data('id');
            currentImage = images.find(image => image.id === currentImageId);
            let isActive = false;
            if ($(this).hasClass('active')) {
                isActive = true;
            }
            $('.image-list-item').removeClass('active');
            if (!isActive) {
                $(this).addClass('active');
                $('#delete-button').removeClass('disable');
            } else {
                currentImageId = 0;
                currentImage = null;
                $('#delete-button').addClass('disable');
            }
        });
    });
}

function updateTags() {
    $('#tag-list').empty();
    tagsCategory.forEach(tag => {
        let newDiv = `<div class="tag-list-item" data-id="` + tag.id + `">` + tag.name + `</div>`;
        $('#tag-list').append(newDiv);
        $('.tag-list-item:last-of-type').on('click', function(e) {
            e.preventDefault();
            let isActive = false;
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
            }
            let tagsId = [];
            $('.tag-list-item.active').each(function() {
                tagsId.push($(this).data('id'));
            });
            if (tagsId.length === 0) {
                updateImages();
                return;
            } else {
                let images = user.images.filter(image => {
                    let tags = JSON.parse(image.tags);
                    return tags.find(tag => tagsId.includes(tag.id));
                });
                updateImages(images);
            }
        });
    });
}

function round(value, precision) {
    var multiplier = Math.pow(10, precision || 0);
    return Math.round(value * multiplier) / multiplier;
}