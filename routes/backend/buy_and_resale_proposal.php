<?php

/**
 * All route names are prefixed with 'admin.auth'.
 */
Route::group([
    'namespace'  => 'BuyAndResaleProposal',
], function () {

    Route::get('buy-and-resale-proposal/deleted', 'BuyAndResaleProposalStatusController@getDeleted')->name('buy-and-resale-proposal.deleted');
    Route::patch('buy-and-resale-proposal/{buyAndResaleProposal}/accept', 'BuyAndResaleProposalStatusController@acceptProposal')->name('buy-and-resale-proposal.accept');
    Route::patch('buy-and-resale-proposal/{buyAndResaleProposal}/send-to-assistant', 'BuyAndResaleProposalStatusController@sendToAssistant')->name('buy-and-resale-proposal.send-to-assistant');
    Route::patch('buy-and-resale-proposal/{buyAndResaleProposal}/send-to-collector', 'BuyAndResaleProposalStatusController@sendToCollector')->name('buy-and-resale-proposal.send-to-collector');
    Route::patch('buy-and-resale-proposal/{buyAndResaleProposal}/collect', 'BuyAndResaleProposalStatusController@collect')->name('buy-and-resale-proposal.collect');

    Route::patch('buy-and-resale-proposal/item/{item}/delivery-status', 'BuyAndResaleProposalController@changeItemDeliveryStatus')->name('buy-and-resale-proposal.update-item-delivery');

    Route::resource('buy-and-resale-proposal', 'BuyAndResaleProposalController');

    Route::get('buy-and-resale-proposal/export/{buyAndSellProposal}', 'BuyAndResaleProposalController@exportBuyAndResaleProposal')->name('buy-and-resale-proposal.export_pending_bns_proposal');

    Route::group(['prefix' => 'buy-and-resale/{deletedBuyAndResaleProposal}'], function () {
        Route::get('delete', 'BuyAndResaleProposalStatusController@delete')->name('buy-and-resale.delete-permanently');
        Route::get('restore', 'BuyAndResaleProposalStatusController@restore')->name('buy-and-resale.restore');
    });

});
