<div class="faq-container_2">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-12">
			<div class="faq-item_2 rounded">
				<h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between"
					data-bs-toggle="collapse" data-bs-target="#faq{{$i}}">

{{$massivTovars[$i]->name}}
					<span class="faq-heading-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="icon icon-down">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
				    </span>
				</h2>
				<div id="faq{{$i}}" class="accordion-collapse collapse">
					<p class="faq-body text_14">

{{$massivTovars[$i]->text}}
<br><br>
Стоимость - {{$massivTovars[$i]->price}} ₽.


					</p>
				</div>
			</div>
		</div>
	</div>
</div>


{{-- 
Морж — суровый, но добродушный боцман морской команды, мастер на все лапы!
           Эта мягкая игрушка ростом 42 см со строгим капитанским взглядом отвечает за порядок на корабле.
Эта мягкая игрушка гроза морей и полок, ростом 42 см! Этот суровый полярный командир одним строгим взглядом наводит порядок на корабле и в вашей комнате. Готов рулить волнами и вашим настроением — берите, пока не отдал швартовы!
Его AI-аватар оживает через QR-код, рассказывая захватывающие истории о морских приключениях, обучая детей основам навигации и морским узлам.
Морж любит делиться шутками и загадками, а его хриплый голос и мудрые советы делают его верным другом для юных моряков!
--}}
