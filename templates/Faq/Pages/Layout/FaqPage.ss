<% include PageHeader %>
<% require themedCSS('components/_faq') %>

<div class="container faq__page mb-5">
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col-12 my-4 typography">
                    <% loop $FAQs %>
                        <div class="py-2 question toggle faq_toggle{$ID} mb-0">
                            <h4 class="question__headline{$ID} mb-0">$Question <span class="position-relative plusminus right"><i class="fal fa-plus"></i></span> </h4>
                        </div>
                        <div class="answer{$ID}" style="display: none">
                            <p>$Answer</p>
                        </div>
                        <hr>
                        <script>
                            onDomReady(function() {
                                $('.faq_toggle{$ID}').click(function () {
                                    $('.answer{$ID}').toggle();
                                    $('.question__headline{$ID}').toggleClass('color')
                                });
                                $('.faq_toggle{$ID}').click(function(){
                                    $(this).find('i').toggleClass('fal fa-plus fal fa-minus')
                                });
                            });
                        </script>
                    <% end_loop %>
                </div>
            </div>
        </div>
    </div>
</div>