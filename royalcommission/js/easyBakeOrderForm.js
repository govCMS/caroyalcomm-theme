!function(e,a,n,t,r){a.behaviors.validateEasyBakeOrderForm={attach:function(a,r){function s(e){if(0==t.getElementsByClassName("messages validate").length){var a,n,r=t.getElementById("page-title"),s=t.createElement("div"),i=t.createElement("h2"),l=t.createElement("ul");if(s.className="messages--error messages error validate",i.className="element-invisible",i.innerHTML="Error message",s.appendChild(i),e.length>1){l.className="messages__list";for(var m=0;m<e.length;m++)(a=t.createElement("li")).className="messages__item",(n=t.createElement("pre")).innerHTML=e[m].message,a.appendChild(n),l.appendChild(a);s.appendChild(l)}else n=t.createTextNode(e[0].message),s.appendChild(n);r.parentNode.insertBefore(s,r.nextSibling)}}var i,l,m;if(r.ezBake&&(r.ezBake.bakerURL&&(l=r.ezBake.bakerURL),r.ezBake.confirmPageURL&&(m=r.ezBake.confirmPageURL),r.ezBake.errorPageURL&&r.ezBake.errorPageURL),(i=e('form[name="easybake-order-form"]'))&&i.length>0){new FormValidator("easybake-order-form",[{name:"contact_name",display:"name",rules:"required"},{name:"contact_email",display:"email",rules:"required|valid_email|callback_gov_email"},{name:"phone_number",display:"phone number",rules:"required"},{name:"site_name",display:"site name",rules:"required"},{name:"agency_name",display:"agency name",rules:"required"}],function(a,r){if(a.length>0)s(a);else{var i=t.getElementsByClassName("messages validate");i.length>0&&i[0].parentNode.removeChild(i[0]);var o=r.target||r.srcElement;!function(a,t){if(l){var r={contact_name:a.find("input[name='contact_name']").val(),contact_email:a.find("input[name='contact_email']").val(),contact_phone:a.find("input[name='phone_number']").val(),site_name:a.find("input[name='site_name']").val(),agency_name:a.find("input[name='agency_name']").val()},i=a.find("textarea[name='website_purpose']").val();i&&(r.website_purpose=i);var o=l+"/order/submit";e.ajax({url:o,type:"POST",data:r,async:!1,success:function(e){m&&n.location.replace(m)},error:function(e,a){var n=JSON.parse(e.response),t="Error: ";t+=n.error,t+="\nDetails: ",n.details&&(t+=n.details.message),s([{message:t}])}})}else s([{message:"This form cannot be submitted at the moment. Please contact your administrator for more information."}]);t.preventDefault()}(e(o),r)}}).registerCallback("gov_email",function(e){return/gov\.au$/.test(e)}).setMessage("gov_email","We're sorry, the govCMS service is only available to Australian government entities so we require you to have a valid .gov.au email address. If you are part of a government entity that doesn't use .gov.au email addresses, please get in touch and we can help you.")}}}}(jQuery,Drupal,this,this.document);