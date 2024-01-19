<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    /*Annual Report*/
    Route::get('documents-download/annual-report', 'AnnualreportController@getIndex')->name('admin.documents-download.annual-report.index');
    Route::get('documents-download/annual-report/create', 'AnnualreportController@getCreate')->name('admin.documents-download.annual-report.create');
    Route::post('documents-download/annual-report/create', 'AnnualreportController@postCreate')->name('admin.documents-download.annual-report.create');
    Route::get('documents-download/annual-report/edit/{id}', 'AnnualreportController@getEdit')->name('admin.documents-download.annual-report.edit');
    Route::post('documents-download/annual-report/edit/{id}', 'AnnualreportController@postEdit')->name('admin.documents-download.annual-report.edit');
    Route::get('documents-download/annual-report/delete/{id}', 'AnnualreportController@getDelete')->name('admin.documents-download.annual-report.delete');
    Route::post('documents-download/annual-report/deleteAll', 'AnnualreportController@postDeleteAll')->name('admin.documents-download.annual-report.deleteAll');

    /*Financial Statement Quarter*/
    Route::get('documents-download/financial-statement-quarter', 'FinanciastatementquarterlController@getIndex')->name('admin.documents-download.financial-statement-quarter.index');
    Route::get('documents-download/financial-statement-quarter/create', 'FinanciastatementquarterlController@getCreate')->name('admin.documents-download.financial-statement-quarter.create');
    Route::post('documents-download/financial-statement-quarter/create', 'FinanciastatementquarterlController@postCreate')->name('admin.documents-download.financial-statement-quarter.create');
    Route::get('documents-download/financial-statement-quarter/edit/{id}', 'FinanciastatementquarterlController@getEdit')->name('admin.documents-download.financial-statement-quarter.edit');
    Route::post('documents-download/financial-statement-quarter/edit/{id}', 'FinanciastatementquarterlController@postEdit')->name('admin.documents-download.financial-statement-quarter.edit');
    Route::get('documents-download/financial-statement-quarter/delete/{id}', 'FinanciastatementquarterlController@getDelete')->name('admin.documents-download.financial-statement-quarter.delete');
    Route::post('documents-download/financial-statement-quarter/deleteAll', 'FinanciastatementquarterlController@postDeleteAll')->name('admin.documents-download.financial-statement-quarter.deleteAll');

    /* Shareholders Report */
    Route::get('documents-download/shareholders-report', 'ShareholdersreportController@getIndex')->name('admin.documents-download.shareholders-report.index');
    Route::get('documents-download/shareholders-report/create', 'ShareholdersreportController@getCreate')->name('admin.documents-download.shareholders-report.create');
    Route::post('documents-download/shareholders-report/create', 'ShareholdersreportController@postCreate')->name('admin.documents-download.shareholders-report.create');
    Route::get('documents-download/shareholders-report/edit/{id}', 'ShareholdersreportController@getEdit')->name('admin.documents-download.shareholders-report.edit');
    Route::post('documents-download/shareholders-report/edit/{id}', 'ShareholdersreportController@postEdit')->name('admin.documents-download.shareholders-report.edit');
    Route::get('documents-download/shareholders-report/delete/{id}', 'ShareholdersreportController@getDelete')->name('admin.documents-download.shareholders-report.delete');
    Route::post('documents-download/shareholders-report/deleteAll', 'ShareholdersreportController@postDeleteAll')->name('admin.documents-download.shareholders-report.deleteAll');


    /* Business Ethics */
    Route::get('documents-download/business-ethics', 'BusinessethicsController@getIndex')->name('admin.documents-download.business-ethics.index');
    Route::get('documents-download/business-ethics/create', 'BusinessethicsController@getCreate')->name('admin.documents-download.business-ethics.create');
    Route::post('documents-download/business-ethics/create', 'BusinessethicsController@postCreate')->name('admin.documents-download.business-ethics.create');
    Route::get('documents-download/business-ethics/edit/{id}', 'BusinessethicsController@getEdit')->name('admin.documents-download.business-ethics.edit');
    Route::post('documents-download/business-ethics/edit/{id}', 'BusinessethicsController@postEdit')->name('admin.documents-download.business-ethics.edit');
    Route::get('documents-download/business-ethics/delete/{id}', 'BusinessethicsController@getDelete')->name('admin.documents-download.business-ethics.delete');
    Route::post('documents-download/business-ethics/deleteAll', 'BusinessethicsController@postDeleteAll')->name('admin.documents-download.business-ethics.deleteAll');


    /* Company Certification of Association */
    Route::get('documents-download/company-certification-of-association','CompanycertificationofassociationController@getIndex')->name('admin.documents-download.company-certification-of-association.index');
    Route::get('documents-download/company-certification-of-association/create', 'CompanycertificationofassociationController@getCreate')->name('admin.documents-download.company-certification-of-association.create');
    Route::post('documents-download/company-certification-of-association/create', 'CompanycertificationofassociationController@postCreate')->name('admin.documents-download.company-certification-of-association.create');
    Route::get('documents-download/company-certification-of-association/edit/{id}', 'CompanycertificationofassociationController@getEdit')->name('admin.documents-download.company-certification-of-association.edit');
    Route::post('documents-download/company-certification-of-association/edit/{id}', 'CompanycertificationofassociationController@postEdit')->name('admin.documents-download.company-certification-of-association.edit');
    Route::get('documents-download/company-certification-of-association/delete/{id}', 'CompanycertificationofassociationController@getDelete')->name('admin.documents-download.company-certification-of-association.delete');
    Route::post('documents-download/company-certification-of-association/deleteAll', 'CompanycertificationofassociationController@postDeleteAll')->name('admin.documents-download.company-certification-of-association.deleteAll');

    /* Company Regulation */
    Route::get('documents-download/company-regulation','CompanyregulationController@getIndex')->name('admin.documents-download.company-regulation.index');
    Route::get('documents-download/company-regulation/create', 'CompanyregulationController@getCreate')->name('admin.documents-download.company-regulation.create');
    Route::post('documents-download/company-regulation/create', 'CompanyregulationController@postCreate')->name('admin.documents-download.company-regulation.create');
    Route::get('documents-download/company-regulation/edit/{id}', 'CompanyregulationController@getEdit')->name('admin.documents-download.company-regulation.edit');
    Route::post('documents-download/company-regulation/edit/{id}', 'CompanyregulationController@postEdit')->name('admin.documents-download.company-regulation.edit');
    Route::get('documents-download/company-regulation/delete/{id}', 'CompanyregulationController@getDelete')->name('admin.documents-download.company-regulation.delete');
    Route::post('documents-download/company-regulation/deleteAll', 'CompanyregulationController@postDeleteAll')->name('admin.documents-download.company-regulation.deleteAll');

});

Route::group(['prefix'=>LaravelLocalization::setLocale(),'middleware'=>['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('preview/flipbook/{id}','DocumentController@getFlipbook')->name('preview-flipbook');
});
