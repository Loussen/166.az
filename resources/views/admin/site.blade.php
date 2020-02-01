@php
    $title = 'Site info';
@endphp

@extends('admin.index')

@section('title') {{ $title }} @endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="m-portlet m-portlet--creative">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                <span>About us</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <form action="{{ route( 'admin.site.edit' ) }}" method="post" x-edit-form class="m-form m-form--fit m-form--label-align-right">
                    <input type="hidden" name="form" value="1">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-sm-4 form-group m-form__group upload-photo" x-photo>
                                    <label>Photo</label>
                                    <img src="{{ media( 'site/' . $site -> photo ) }}" x-photo-img="photo" x-photo-default="{{ media( 'site' , $site -> photo ) }}" alt="">
                                    <input type="file" name="photo" accept="image/.*" x-photo-input="photo">
                                </div>
                                <div class="col-sm-4 form-group m-form__group upload-photo" x-photo>
                                    <label>Background</label>
                                    <img src="{{ media( 'site/' . $site -> background ) }}" x-photo-img="background" x-photo-default="{{ media( 'site' , $site -> background ) }}" alt="">
                                    <input type="file" name="background" accept="image/.*" x-photo-input="background">
                                </div>
                                <div class="col-sm-4 form-group m-form__group upload-photo" x-photo>
                                    <label>Open Graph image</label>
                                    <img src="{{ media( 'site/' . $site -> og_image ) }}" x-photo-img="og_image" x-photo-default="{{ media( 'site' , $site -> og_image ) }}" alt="">
                                    <input type="file" name="og_image" accept="image/.*" x-photo-input="og_image">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Title ( EN )</label>
                                    <textarea rows="5" name="title_en" class="form-control m-input m-input--air" placeholder="Title ( EN )">{{ $site -> title_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Title ( AZ )</label>
                                    <textarea rows="5" name="title_az" class="form-control m-input m-input--air" placeholder="Title ( AZ )">{{ $site -> title_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Title ( RU )</label>
                                    <textarea rows="5" name="title_ru" class="form-control m-input m-input--air" placeholder="Title ( RU )">{{ $site -> title_ru }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Text ( EN )</label>
                                    <textarea x-summernote name="text_en" class="form-control m-input m-input--air" placeholder="Text ( EN )">{{ $site -> text_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Text ( AZ )</label>
                                    <textarea x-summernote name="text_az" class="form-control m-input m-input--air" placeholder="Text ( AZ )">{{ $site -> text_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Text ( RU )</label>
                                    <textarea x-summernote name="text_ru" class="form-control m-input m-input--air" placeholder="Text ( RU )">{{ $site -> text_ru }}</textarea>
                                </div>
                                <div class="col-sm-3 form-group m-form__group">
                                    <label>Transported objects</label>
                                    <input name="transported_objects" class="form-control m-input m-input--air" value="{{ $site -> transported_objects }}">
                                </div>
                                <div class="col-sm-3 form-group m-form__group">
                                    <label>Cleaned places</label>
                                    <input name="cleaned_places" class="form-control m-input m-input--air" value="{{ $site -> cleaned_places }}">
                                </div>
                                <div class="col-sm-3 form-group m-form__group">
                                    <label>Customer reviews</label>
                                    <input name="customer_reviews" class="form-control m-input m-input--air" value="{{ $site -> customer_reviews }}">
                                </div>
                                <div class="col-sm-3 form-group m-form__group">
                                    <label>Satisfied customers</label>
                                    <input name="satisfied_customers" class="form-control m-input m-input--air" value="{{ $site -> satisfied_customers }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Mission ( EN )</label>
                                    <textarea x-summernote name="mission_en" class="form-control m-input m-input--air" placeholder="Mission ( EN )">{{ $site -> mission_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Mission ( AZ )</label>
                                    <textarea x-summernote name="mission_az" class="form-control m-input m-input--air" placeholder="Mission ( AZ )">{{ $site -> mission_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Mission ( RU )</label>
                                    <textarea x-summernote name="mission_ru" class="form-control m-input m-input--air" placeholder="Mission ( RU )">{{ $site -> mission_ru }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions">
                            <button type="submit" class="btn m-btn btn-primary pull-right">Edit</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="m-portlet m-portlet--creative">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                <span>Contact details</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <form action="{{ route( 'admin.site.edit' ) }}" method="post" x-edit-form class="m-form m-form--fit m-form--label-align-right">
                    <input type="hidden" name="form" value="2">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Address ( EN )</label>
                                    <input name="address_en" class="form-control m-input m-input--air" placeholder="Address ( EN )" value="{{ $site -> address_en }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Address ( AZ )</label>
                                    <input name="address_az" class="form-control m-input m-input--air" placeholder="Address ( AZ )" value="{{ $site -> address_az }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Address ( RU )</label>
                                    <input name="address_ru" class="form-control m-input m-input--air" placeholder="Address ( RU )" value="{{ $site -> address_ru }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Corporate contact ( EN )</label>
                                    <input name="corporate_contact_en" class="form-control m-input m-input--air" placeholder="Corporate contact ( EN )" value="{{ $site -> corporate_contact_en }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Corporate contact ( AZ )</label>
                                    <input name="corporate_contact_az" class="form-control m-input m-input--air" placeholder="Corporate contact ( AZ )" value="{{ $site -> corporate_contact_az }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Corporate contact ( RU )</label>
                                    <input name="corporate_contact_ru" class="form-control m-input m-input--air" placeholder="Corporate contact ( RU )" value="{{ $site -> corporate_contact_ru }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Post index</label>
                                    <input name="index" class="form-control m-input m-input--air" placeholder="Post index" value="{{ $site -> index }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Mobile</label>
                                    <input name="mobile" class="form-control m-input m-input--air" placeholder="Mobile" value="{{ $site -> mobile }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Email</label>
                                    <input name="email" class="form-control m-input m-input--air" placeholder="Email" value="{{ $site -> email }}">
                                </div>
                                <div class="col-sm-3 form-group m-form__group">
                                    <label>Ad mobile</label>
                                    <input name="ad_mobile" class="form-control m-input m-input--air" placeholder="Ad mobile" value="{{ $site -> ad_mobile }}">
                                </div>
                                <div class="col-sm-3 form-group m-form__group">
                                    <label>Ad email</label>
                                    <input name="ad_email" class="form-control m-input m-input--air" placeholder="Ad email" value="{{ $site -> ad_email }}">
                                </div>
                                <div class="col-sm-3 form-group m-form__group">
                                    <label>Order mobile</label>
                                    <input name="order_mobile" class="form-control m-input m-input--air" placeholder="Order mobile" value="{{ $site -> order_mobile }}">
                                </div>
                                <div class="col-sm-3 form-group m-form__group">
                                    <label>Order email</label>
                                    <input name="order_email" class="form-control m-input m-input--air" placeholder="Order email" value="{{ $site -> order_email }}">
                                </div>
                                <div class="col-sm-3 form-group m-form__group">
                                    <label>Facebook</label>
                                    <input name="facebook" class="form-control m-input m-input--air" placeholder="Facebook" value="{{ $site -> facebook }}">
                                </div>
                                <div class="col-sm-3 form-group m-form__group">
                                    <label>Instagram</label>
                                    <input name="instagram" class="form-control m-input m-input--air" placeholder="Instagram" value="{{ $site -> instagram }}">
                                </div>
                                <div class="col-sm-3 form-group m-form__group">
                                    <label>Youtube</label>
                                    <input name="youtube" class="form-control m-input m-input--air" placeholder="Youtube" value="{{ $site -> youtube }}">
                                </div>
                                <div class="col-sm-3 form-group m-form__group">
                                    <label>Linkedin</label>
                                    <input name="linkedin" class="form-control m-input m-input--air" placeholder="Linkedin" value="{{ $site -> linkedin }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions">
                            <button type="submit" class="btn m-btn btn-primary pull-right">Edit</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="m-portlet m-portlet--creative">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                <span>Page headlines</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <form action="{{ route( 'admin.site.edit' ) }}" method="post" x-edit-form class="m-form m-form--fit m-form--label-align-right">
                    <input type="hidden" name="form" value="3">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Services ( EN )</label>
                                    <textarea rows="10" name="services_headline_en" class="form-control m-input m-input--air" placeholder="Services ( EN )">{{ $site -> services_headline_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Services ( AZ )</label>
                                    <textarea rows="10" name="services_headline_az" class="form-control m-input m-input--air" placeholder="Services ( AZ )">{{ $site -> services_headline_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Services ( RU )</label>
                                    <textarea rows="10" name="services_headline_ru" class="form-control m-input m-input--air" placeholder="Services ( RU )">{{ $site -> services_headline_ru }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Cars ( EN )</label>
                                    <textarea rows="10" name="cars_headline_en" class="form-control m-input m-input--air" placeholder="Cars ( EN )">{{ $site -> cars_headline_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Cars ( AZ )</label>
                                    <textarea rows="10" name="cars_headline_az" class="form-control m-input m-input--air" placeholder="Cars ( AZ )">{{ $site -> cars_headline_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Cars ( RU )</label>
                                    <textarea rows="10" name="cars_headline_ru" class="form-control m-input m-input--air" placeholder="Cars ( RU )">{{ $site -> cars_headline_ru }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Campaigns ( EN )</label>
                                    <textarea rows="10" name="campaigns_headline_en" class="form-control m-input m-input--air" placeholder="Campaigns ( EN )">{{ $site -> campaigns_headline_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Campaigns ( AZ )</label>
                                    <textarea rows="10" name="campaigns_headline_az" class="form-control m-input m-input--air" placeholder="Campaigns ( AZ )">{{ $site -> campaigns_headline_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Campaigns ( RU )</label>
                                    <textarea rows="10" name="campaigns_headline_ru" class="form-control m-input m-input--air" placeholder="Campaigns ( RU )">{{ $site -> campaigns_headline_ru }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Customers ( EN )</label>
                                    <textarea rows="10" name="customers_headline_en" class="form-control m-input m-input--air" placeholder="Customers ( EN )">{{ $site -> customers_headline_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Customers ( AZ )</label>
                                    <textarea rows="10" name="customers_headline_az" class="form-control m-input m-input--air" placeholder="Customers ( AZ )">{{ $site -> customers_headline_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Customers ( RU )</label>
                                    <textarea rows="10" name="customers_headline_ru" class="form-control m-input m-input--air" placeholder="Customers ( RU )">{{ $site -> customers_headline_ru }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>FAQ ( EN )</label>
                                    <textarea rows="10" name="faq_headline_en" class="form-control m-input m-input--air" placeholder="FAQ ( EN )">{{ $site -> faq_headline_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>FAQ ( AZ )</label>
                                    <textarea rows="10" name="faq_headline_az" class="form-control m-input m-input--air" placeholder="FAQ ( AZ )">{{ $site -> faq_headline_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>FAQ ( RU )</label>
                                    <textarea rows="10" name="faq_headline_ru" class="form-control m-input m-input--air" placeholder="FAQ ( RU )">{{ $site -> faq_headline_ru }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Academy ( EN )</label>
                                    <textarea x-summernote name="academy_text_en" class="form-control m-input m-input--air" placeholder="Academy ( EN )">{{ $site -> academy_text_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Academy ( AZ )</label>
                                    <textarea x-summernote name="academy_text_az" class="form-control m-input m-input--air" placeholder="Academy ( AZ )">{{ $site -> academy_text_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Academy ( RU )</label>
                                    <textarea x-summernote name="academy_text_ru" class="form-control m-input m-input--air" placeholder="Academy ( RU )">{{ $site -> academy_text_ru }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Team ( EN )</label>
                                    <textarea x-summernote name="team_text_en" class="form-control m-input m-input--air" placeholder="Team ( EN )">{{ $site -> team_text_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Team ( AZ )</label>
                                    <textarea x-summernote name="team_text_az" class="form-control m-input m-input--air" placeholder="Team ( AZ )">{{ $site -> team_text_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Team ( RU )</label>
                                    <textarea x-summernote name="team_text_ru" class="form-control m-input m-input--air" placeholder="Team ( RU )">{{ $site -> team_text_ru }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions">
                            <button type="submit" class="btn m-btn btn-primary pull-right">Edit</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="m-portlet m-portlet--creative">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                <span>SEO</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <form action="{{ route( 'admin.site.edit' ) }}" method="post" x-edit-form class="m-form m-form--fit m-form--label-align-right">
                    <input type="hidden" name="form" value="4">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>About page SEO Keywords ( EN )</label>
                                    <input x-tagsinput name="about_seo_keywords_en" class="form-control m-input m-input--air" placeholder="About page SEO Keywords ( EN )" value="{{ $site -> about_seo_keywords_en }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>About page SEO Keywords ( AZ )</label>
                                    <input x-tagsinput name="about_seo_keywords_az" class="form-control m-input m-input--air" placeholder="About page SEO Keywords ( AZ )" value="{{ $site -> about_seo_keywords_az }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>About page SEO Keywords ( RU )</label>
                                    <input x-tagsinput name="about_seo_keywords_ru" class="form-control m-input m-input--air" placeholder="About page SEO Keywords ( RU )" value="{{ $site -> about_seo_keywords_ru }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>About page SEO Description ( EN )</label>
                                    <textarea rows="5" name="about_seo_description_en" class="form-control m-input m-input--air" placeholder="Services ( EN )">{{ $site -> about_seo_description_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>About page SEO Description ( AZ )</label>
                                    <textarea rows="5" name="about_seo_description_az" class="form-control m-input m-input--air" placeholder="About page SEO Description ( AZ )">{{ $site -> about_seo_description_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>About page SEO Description ( RU )</label>
                                    <textarea rows="5" name="about_seo_description_ru" class="form-control m-input m-input--air" placeholder="About page SEO Description ( RU )">{{ $site -> about_seo_description_ru }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group mt-3">
                                    <label>Autopark page SEO Keywords ( EN )</label>
                                    <input x-tagsinput name="autopark_seo_keywords_en" class="form-control m-input m-input--air" placeholder="Autopark page SEO Keywords ( EN )" value="{{ $site -> autopark_seo_keywords_en }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Autopark page SEO Keywords ( AZ )</label>
                                    <input x-tagsinput name="autopark_seo_keywords_az" class="form-control m-input m-input--air" placeholder="Autopark page SEO Keywords ( AZ )" value="{{ $site -> autopark_seo_keywords_az }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Autopark page SEO Keywords ( RU )</label>
                                    <input x-tagsinput name="autopark_seo_keywords_ru" class="form-control m-input m-input--air" placeholder="Autopark page SEO Keywords ( RU )" value="{{ $site -> autopark_seo_keywords_ru }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Autopark page SEO Description ( EN )</label>
                                    <textarea rows="5" name="autopark_seo_description_en" class="form-control m-input m-input--air" placeholder="Autopark ( EN )">{{ $site -> autopark_seo_description_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Autopark page SEO Description ( AZ )</label>
                                    <textarea rows="5" name="autopark_seo_description_az" class="form-control m-input m-input--air" placeholder="Autopark page SEO Description ( AZ )">{{ $site -> autopark_seo_description_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Autopark page SEO Description ( RU )</label>
                                    <textarea rows="5" name="autopark_seo_description_ru" class="form-control m-input m-input--air" placeholder="Autopark page SEO Description ( RU )">{{ $site -> autopark_seo_description_ru }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group mt-3">
                                    <label>Services page SEO Keywords ( EN )</label>
                                    <input x-tagsinput name="service_seo_keywords_en" class="form-control m-input m-input--air" placeholder="Services page SEO Keywords ( EN )" value="{{ $site -> service_seo_keywords_en }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Services page SEO Keywords ( AZ )</label>
                                    <input x-tagsinput name="service_seo_keywords_az" class="form-control m-input m-input--air" placeholder="Services page SEO Keywords ( AZ )" value="{{ $site -> service_seo_keywords_az }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Services page SEO Keywords ( RU )</label>
                                    <input x-tagsinput name="service_seo_keywords_ru" class="form-control m-input m-input--air" placeholder="Services page SEO Keywords ( RU )" value="{{ $site -> service_seo_keywords_ru }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Services page SEO Description ( EN )</label>
                                    <textarea rows="5" name="service_seo_description_en" class="form-control m-input m-input--air" placeholder="Services ( EN )">{{ $site -> service_seo_description_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Services page SEO Description ( AZ )</label>
                                    <textarea rows="5" name="service_seo_description_az" class="form-control m-input m-input--air" placeholder="Services page SEO Description ( AZ )">{{ $site -> service_seo_description_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Services page SEO Description ( RU )</label>
                                    <textarea rows="5" name="service_seo_description_ru" class="form-control m-input m-input--air" placeholder="Services page SEO Description ( RU )">{{ $site -> service_seo_description_ru }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group mt-3">
                                    <label>Campaigns page SEO Keywords ( EN )</label>
                                    <input x-tagsinput name="campaign_seo_keywords_en" class="form-control m-input m-input--air" placeholder="Campaigns page SEO Keywords ( EN )" value="{{ $site -> campaign_seo_keywords_en }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Campaigns page SEO Keywords ( AZ )</label>
                                    <input x-tagsinput name="campaign_seo_keywords_az" class="form-control m-input m-input--air" placeholder="Campaigns page SEO Keywords ( AZ )" value="{{ $site -> campaign_seo_keywords_az }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Campaigns page SEO Keywords ( RU )</label>
                                    <input x-tagsinput name="campaign_seo_keywords_ru" class="form-control m-input m-input--air" placeholder="Campaigns page SEO Keywords ( RU )" value="{{ $site -> campaign_seo_keywords_ru }}">
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Campaigns page SEO Description ( EN )</label>
                                    <textarea rows="5" name="campaign_seo_description_en" class="form-control m-input m-input--air" placeholder="Campaigns ( EN )">{{ $site -> campaign_seo_description_en }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Campaigns page SEO Description ( AZ )</label>
                                    <textarea rows="5" name="campaign_seo_description_az" class="form-control m-input m-input--air" placeholder="Campaigns page SEO Description ( AZ )">{{ $site -> campaign_seo_description_az }}</textarea>
                                </div>
                                <div class="col-sm-4 form-group m-form__group">
                                    <label>Campaigns page SEO Description ( RU )</label>
                                    <textarea rows="5" name="campaign_seo_description_ru" class="form-control m-input m-input--air" placeholder="Campaigns page SEO Description ( RU )">{{ $site -> campaign_seo_description_ru }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions">
                            <button type="submit" class="btn m-btn btn-primary pull-right">Edit</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="m-portlet m-portlet--creative">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                <span>Page background images</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <form action="{{ route( 'admin.site.edit' ) }}" method="post" x-edit-form class="m-form m-form--fit m-form--label-align-right">
                    <input type="hidden" name="form" value="5">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-sm-2 form-group m-form__group upload-photo" x-photo>
                                    <label>Services page</label>
                                    <img src="{{ media( 'site/' . $site -> service_background ) }}" x-photo-img="service_background" x-photo-default="{{ media( 'site' , $site -> service_background ) }}" alt="">
                                    <input type="file" name="service_background" accept="image/.*" x-photo-input="service_background">
                                </div>
                                <div class="col-sm-2 form-group m-form__group upload-photo" x-photo>
                                    <label>Campaigns page</label>
                                    <img src="{{ media( 'site/' . $site -> campaign_background ) }}" x-photo-img="campaign_background" x-photo-default="{{ media( 'site' , $site -> campaign_background ) }}" alt="">
                                    <input type="file" name="campaign_background" accept="image/.*" x-photo-input="campaign_background">
                                </div>
                                <div class="col-sm-2 form-group m-form__group upload-photo" x-photo>
                                    <label>Autopark page</label>
                                    <img src="{{ media( 'site/' . $site -> autopark_background ) }}" x-photo-img="autopark_background" x-photo-default="{{ media( 'site' , $site -> autopark_background ) }}" alt="">
                                    <input type="file" name="autopark_background" accept="image/.*" x-photo-input="autopark_background">
                                </div>
                                <div class="col-sm-2 form-group m-form__group upload-photo" x-photo>
                                    <label>Our team page</label>
                                    <img src="{{ media( 'site/' . $site -> team_background ) }}" x-photo-img="team_background" x-photo-default="{{ media( 'site' , $site -> team_background ) }}" alt="">
                                    <input type="file" name="team_background" accept="image/.*" x-photo-input="team_background">
                                </div>
                                <div class="col-sm-2 form-group m-form__group upload-photo" x-photo>
                                    <label>Blog page</label>
                                    <img src="{{ media( 'site/' . $site -> blog_background ) }}" x-photo-img="blog_background" x-photo-default="{{ media( 'site' , $site -> blog_background ) }}" alt="">
                                    <input type="file" name="blog_background" accept="image/.*" x-photo-input="blog_background">
                                </div>
                                <div class="col-sm-2 form-group m-form__group upload-photo" x-photo>
                                    <label>We at media page</label>
                                    <img src="{{ media( 'site/' . $site -> media_background ) }}" x-photo-img="media_background" x-photo-default="{{ media( 'site' , $site -> media_background ) }}" alt="">
                                    <input type="file" name="media_background" accept="image/.*" x-photo-input="media_background">
                                </div>
                                <div class="col-sm-2 form-group m-form__group upload-photo" x-photo>
                                    <label>FAQ page</label>
                                    <img src="{{ media( 'site/' . $site -> faq_background ) }}" x-photo-img="faq_background" x-photo-default="{{ media( 'site' , $site -> faq_background ) }}" alt="">
                                    <input type="file" name="faq_background" accept="image/.*" x-photo-input="faq_background">
                                </div>
                                <div class="col-sm-2 form-group m-form__group upload-photo" x-photo>
                                    <label>Gallery page</label>
                                    <img src="{{ media( 'site/' . $site -> gallery_background ) }}" x-photo-img="gallery_background" x-photo-default="{{ media( 'site' , $site -> gallery_background ) }}" alt="">
                                    <input type="file" name="gallery_background" accept="image/.*" x-photo-input="gallery_background">
                                </div>
                                <div class="col-sm-2 form-group m-form__group upload-photo" x-photo>
                                    <label>Carreer page</label>
                                    <img src="{{ media( 'site/' . $site -> career_background ) }}" x-photo-img="career_background" x-photo-default="{{ media( 'site' , $site -> career_background ) }}" alt="">
                                    <input type="file" name="career_background" accept="image/.*" x-photo-input="career_background">
                                </div>
                                <div class="col-sm-2 form-group m-form__group upload-photo" x-photo>
                                    <label>Contact page</label>
                                    <img src="{{ media( 'site/' . $site -> contact_background ) }}" x-photo-img="contact_background" x-photo-default="{{ media( 'site' , $site -> contact_background ) }}" alt="">
                                    <input type="file" name="contact_background" accept="image/.*" x-photo-input="contact_background">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions">
                            <button type="submit" class="btn m-btn btn-primary pull-right">Edit</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection
