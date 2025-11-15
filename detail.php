<?php
    include('header.php');
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .zoom-container {
      position: relative;
      overflow: hidden;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      max-height: 400px;
    }
    .zoom-image {
      width: 100%;
      height: auto;
      transition: transform 0.2s ease, transform-origin 0.2s ease;
      transform-origin: center center;
    }

    /* Tabs: hide non-active tab panels and allow Tailwind classes for active button styling */
    .tab-content { display: none; }
    .tab-content.active { display: block; }
  </style>

    <section class="py-1 overflow-hidden">
      <div class="container-fluid">
        
        <div class="row">
          <div class="col-md-12">
            <div class="bg-gray-100">
              <div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow-lg mt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                  <!-- Product Image Section -->
                  <div class="zoom-container">
                    <img id="productImage" src="" alt="" class="zoom-image" />
                  </div>

                  <!-- Product Details Section -->
                  <div>
                    <h1 class="text-2xl font-semibold text-gray-800 prodName"></h1>
                    <p class="text-gray-600 mt-2">Brand: <span class="font-medium">Cosmeds</span></p>
                    <div class="flex items-center mt-2">
                      <span class="text-yellow-400 text-lg">★★★★☆</span>
                      <span class="ml-2 text-sm text-gray-500">(132 Reviews)</span>
                    </div>
                    <div class="mt-4">
                      <div class="row"><p class="text-3xl font-bold text-green-600"><span class="disPrice"></span><small>INR</small></p></div>
                      <div class="row"><p class="text-gray-400 line-through ml-3"><span class="price"></span><small>INR</small></p></div>
                      <div class="row"><p class="text-red-500 ml-2"><span class="discount"></span><small>% off</small></p></div>
                    </div>
                    <p class="mt-4 text-gray-700 leading-relaxed description"></P>

                    <div class="mt-6">
                      <label class="block text-sm font-medium text-gray-700">Quantity</label>
                      <input type="number" value="1" min="1" class="border rounded-md w-24 px-2 py-1 mt-1" />
                    </div>

                    <div class="mt-6 flex gap-4">
                      <button class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition">Add to Cart</button>
                      <button class="bg-yellow-500 text-white px-6 py-2 rounded-md hover:bg-yellow-600 transition">Buy Now</button>
                    </div>
                  </div>
                </div>

                <!-- Product Description Tabs -->
                <div class="mt-10">
                  <div class="border-b border-gray-200 flex space-x-6">
                    <button class="tab-btn py-2 px-4 text-gray-600 border-b-2 border-transparent hover:border-green-600" data-tab="description">Description</button>
                    <button class="tab-btn py-2 px-4 text-gray-600 border-b-2 border-transparent hover:border-green-600" data-tab="details">Details</button>
                    <button class="tab-btn py-2 px-4 text-gray-600 border-b-2 border-transparent hover:border-green-600" data-tab="reviews">Reviews</button>
                  </div>

                  <div id="description" class="tab-content active p-4">
                    <h3 class="text-lg font-semibold mb-2">Product Description</h3>
                    <p class="text-gray-700 leading-relaxed description"></p>
                  </div>

                  <div id="details" class="tab-content p-4">
                    <h3 class="text-lg font-semibold mb-2">Product Details</h3>
                    <ul class="list-disc ml-6 text-gray-700">
                      <li>Brand: Cosmeds</li>
                      <li>Item Weight: 50g</li>
                      <li>Skin Type: All</li>
                      <li>Marketed by: Sharma Medical Store, Pathalgaon</li>
                      <li>Country of Origin: India</li>
                    </ul>
                  </div>

                  <div id="reviews" class="tab-content p-4">
                    <h3 class="text-lg font-semibold mb-2">Customer Reviews</h3>
                    <p class="text-gray-700 mb-4">Based on 132 reviews.</p>
                    <div class="space-y-4">
                      <div class="border rounded-md p-4">
                        <p class="text-yellow-400">★★★★★</p>
                        <p class="font-medium">Aarav K.</p>
                        <p class="text-gray-700 text-sm mt-1">Amazing product! My skin feels smoother and well-hydrated.</p>
                      </div>
                      <div class="border rounded-md p-4">
                        <p class="text-yellow-400">★★★★☆</p>
                        <p class="font-medium">Priya S.</p>
                        <p class="text-gray-700 text-sm mt-1">Good texture and fast absorption. Worth the price.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              
            </div>
            

          </div>
        </div>
      </div>
    </section>


    <?php
        include('footer.php');
    ?>
  </body>
</html>
<script>
  document.addEventListener('DOMContentLoaded', function(){
    // Tab switch functionality
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');
    if(tabs.length && contents.length){
      tabs.forEach(tab => {
        tab.addEventListener('click', () => {
          contents.forEach(c => c.classList.remove('active'));
          tabs.forEach(t => t.classList.remove('border-green-600', 'text-green-600'));
          const target = document.getElementById(tab.dataset.tab);
          if(target) target.classList.add('active');
          tab.classList.add('border-green-600', 'text-green-600');
        });
      });
      // ensure first tab is active if none set
      if(!document.querySelector('.tab-content.active')){
        const firstTab = tabs[0];
        if(firstTab){
          const target = document.getElementById(firstTab.dataset.tab);
          if(target) target.classList.add('active');
          firstTab.classList.add('border-green-600', 'text-green-600');
        }
      }
    }

    // Zoom effect controlled by cursor
    const zoomContainer = document.querySelector('.zoom-container');
    const zoomImage = document.querySelector('.zoom-image');
    if(zoomContainer && zoomImage){
      zoomContainer.addEventListener('mousemove', function(e) {
        const rect = zoomContainer.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        zoomImage.style.transformOrigin = `${x}% ${y}%`;
        zoomImage.style.transform = 'scale(2)';
      });

      zoomContainer.addEventListener('mouseleave', function() {
        zoomImage.style.transformOrigin = 'center center';
        zoomImage.style.transform = 'scale(1)';
      });
    }
  });

  var params = new URLSearchParams(window.location.search);
  const id=params.get('id');
  setTimeout(() => { 
    $.ajax({
        url: `${base_url}/api/user/routes.php`, //the page containing php script
        type: "post", //request type,
        dataType: 'json',
        data: {"endurl":"getUniqueProduct",id},
        
        success: function(result) {
            const data = result.data;
            const actPrice = data.price;
            const discount = data.discount;
            const discountedPrice = actPrice - (actPrice * discount / 100);
            
              $('#productImage').attr('src',`${data.url}`);
              $('#productImage').attr('alt',`${data.product_name}`);

              $(".price").text(actPrice);
              $(".disPrice").text(discountedPrice);
              $(".discount").text(data.discount);
              $(".description").text(data.description);
              $(".prodName").text(data.product_name);
            
        },
        error: function(result) {
            console.log (result);
            toastr.error(result.responseJSON.msg, {
                timeOut: 5000
            });
            //location.replace("./login.php");
        },
    });
  }, 2000);
</script>