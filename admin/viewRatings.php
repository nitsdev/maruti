<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Ratings</title>
        <?php
            include('css.php')
        ?>
    </head>
    <body class="sb-nav-fixed">
    <?php
        include('assets/navbar.php')
    ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="col-md-11 prodHead">

                    </div>
                    <div class="container-fluid px-4">
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Ratings</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Ratings
                            </div>
                            
                            <div class="card-body text-center">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Added by</th>
                                            <th>Rating</th>
                                            <th>Comment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="id" id="id" value="" />
                    <input type="hidden" name="deleteRatingId" value="" id="deleteRatingId">
                </main>
                <?php
                include('assets/footer.php')
                ?>
            </div>
        </div>
        <?php
            include('js.php')
        ?>
    </body>
</html>

<script>
    // AJAX Call
    const token = localStorage.getItem("token");
    var params = new URLSearchParams(window.location.search);
    const pid=params.get('pid');
    $("#id").val(pid);

        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getRatings","pid":pid},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                const data = result.data;
                const commentsData = result.commentsData;
                const prodData = result.prodData;
                
                const prodHtml = `<h1 class="mt-4 ml-2"> <small class= "text-primary">Ratings for</small> - ${prodData.product_name}</h1>`;                     
                $('.prodHead').append(prodHtml);
                const product_id = prodData.id;
                
                for(let rating = 0; rating<data.length; rating++){
                    let ratingStar="";
                    let starCount =data[rating].rating;
                    let user_id =data[rating].user_id;
                    for(let star = 0; star<5; star++){
                        if(starCount<=0){
                            ratingStar+="<small class='far fa-star'></small>";
                        }else{
                            ratingStar+="<small class='fas fa-star'></small>";
                        }
                        starCount--;
                    }

                    const comment = commentsData.filter(el => el.product_id == product_id && el.user_id == user_id);

                    const ratingHtml = `<tr>
                                            <td>${data[rating].id}</td>
                                            <td>${data[rating].first_name} ${data[rating].last_name}</td>
                                            <td>${ratingStar}</td>
                                            <td>${comment[0]?.comment || ""}</td>
                                            <td>
                                                <i class="fa-solid fa-trash m-2" onClick=deleteRatingConfirm(${data[rating].id},event) title="Delete Rating" style="color:red"></i>
                                            </td>
                                        </tr>`; 
                    
                    $('#datatablesSimple').find('tbody').append(ratingHtml);
                }
                
            },
            error: function(result) {
                console.log (result);
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
                location.replace("./login.php");
            },
        });

        function deleteRatingConfirm(id,e){
        e.preventDefault();
        $("#deleteRatingId").val(id);
        const deleteRatingFlag = confirm(`Are you sure want to delete this rating Id- ${id} ?`);
        if (deleteRatingFlag == true) {
            deleteRating(id);
        }
    }

    function deleteRating(id){
        $("#deleteRatingId").val(0);
        
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"deleteRating","id":id},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                toastr.success(result.msg, {
                    timeOut: 5000
                });
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    }

</script>