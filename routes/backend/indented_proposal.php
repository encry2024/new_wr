<?php

/**
 * All route names are prefixed with 'admin.auth'.
 */
Route::group([
    'namespace'  => 'IndentedProposal',
], function () {

    Route::get('indented-proposal/deleted', 'IndentedProposalStatusController@getDeleted')->name('indented-proposal.deleted');
    Route::patch('indented-proposal/{indentedProposal}/accept', 'IndentedProposalStatusController@acceptProposal')->name('indented-proposal.accept');
    Route::patch('indented-proposal/{indentedProposal}/send-to-assistant', 'IndentedProposalStatusController@sendToAssistant')->name('indented-proposal.send-to-assistant');
    Route::patch('indented-proposal/{indentedProposal}/send-to-collector', 'IndentedProposalStatusController@sendToCollector')->name('indented-proposal.send-to-collector');
    Route::patch('indented-proposal/{indentedProposal}/collect', 'IndentedProposalStatusController@collect')->name('indented-proposal.collect');

    Route::patch('indented-proposal/item/{item}/delivery-status', 'IndentedProposalController@changeItemDeliveryStatus')->name('indented-proposal.update-item-delivery');

    Route::resource('indented-proposal', 'IndentedProposalController');

    set_time_limit(0);

    Route::get('indented-proposal/export/{indented_proposal}', 'IndentedProposalController@exportPendingProposal')->name('indented-proposal.export_pending_proposal');
    Route::get('indented-proposal/order_entry/{indented_proposal}', 'IndentedProposalController@exportOrderEntry')->name('indented-proposal.se_export_order_entry');


    Route::group(['prefix' => 'indented-proposal/{deletedIndentedProposal}'], function () {
        Route::get('delete', 'IndentedProposalStatusController@delete')->name('indented-proposal.delete-permanently');
        Route::get('restore', 'IndentedProposalStatusController@restore')->name('indented-proposal.restore');
    });

});
